'use strict';
import ProductService from '../services/product';
import StatisticService from '../services/statistic';

// Class definition
let KTStatisticsList = (function () {
    // Elements

    function getFilters() {
        let filters = {};
        let product = $('#filterProduct').val();
        if (product) {
            filters.product = product;
        }
        let rangeMin = $('#filterPriceMin').val();
        let rangeMax = $('#filterPriceMax').val();
        if (rangeMin || rangeMax) {
            filters.price = { min: rangeMin, max: rangeMax };
        }
        rangeMin = $('#filterDateMin').val();
        rangeMax = $('#filterDateMax').val();
        if (rangeMin || rangeMax) {
            filters.date_added = { min: rangeMin, max: rangeMax };
        }

        let target = $('#filterTarget').val();
        if (target) {
            filters.target = target;
        }

        let balanceType = $('#filterBalanceType').val();
        if (balanceType) {
            filters.type = balanceType;
        }

        return filters;
    }

    function applyFilters() {
        let filters = getFilters();
        // $('#exportFilters').val(JSON.stringify(filters));
        StatisticService.getChartData({ filters: JSON.stringify(filters) }, onFilterComplete);
    }

    function onFilterComplete(data, isSuccess) {
        if (!isSuccess) {
            console.log('failed');
            return;
        }
        drawChart(data.daily, 'daily');
        drawChart(data.monthly, 'monthly');
        $('#filtersInfo').text(data.filtersSummary);
    }

    function initializeFilters() {
        $('#toolbarFilter').on('click', 'button[type=submit]', function () {
            applyFilters();
        });

        $('#toolbarFilter').on('click', 'button[type=reset]', function () {
            $('#filterProduct').val('');
            $('#filterPriceMin').val('');
            $('#filterPriceMax').val('');
            $('#filterDateMin').val('').trigger('change');
            $('#filterDateMax').val('').trigger('change');
            $('#filterTarget').val('').trigger('change');
            $('#filterBalanceType').val('').trigger('change');

            applyFilters();
        });

        // added 'prevent-close' class to prevent closing of filters popup when user press apply or close for the daterange
        let rangePickerOptions = {
            singleDatePicker: true,
            minYear: 2022,
            timePicker: false,
            autoApply: true,
            buttonClasses: 'btn btn-sm',
            locale: {
                format: userDateFormat,
            },
        };
        $('#filterDateMin,#filterDateMax').daterangepicker(rangePickerOptions);
        // prevent dropdown menu closing when clicking on calendar
        $('#filterDateMin,#filterDateMax').on('showCalendar.daterangepicker', function (e, picker) {
            // datepicker area (the one around the calendar)
            $(picker.container).addClass('prevent-close');
            // calendar area
            var elem = $(picker.container).find('.table-condensed');
            elem.addClass('prevent-close');
        });
        // make the calendar values empty
        $('#filterDateMin,#filterDateMax').val('').trigger('change');

        ProductService.init({ selector: '#filterProduct', appendTo: '#toolbarFilter' });
    }

    // translate a point from one system of corrdinates to another system of coordinates
    function translatePoint(xS1, xS1Start, xS1End, xS2Start, xS2End) {
        if (xS1End - xS1Start === 0) {
            return 0;
        }
        return xS2Start + ((xS2End - xS2Start) * (xS1 - xS1Start)) / (xS1End - xS1Start);
    }

    function getDrawingCoordinates() {
        return { xStart: 0, xEnd: $('.card-body').width() };
    }

    // round a number to it's nearest value with same length of digits, which can be a multiple of 5 or of 10
    // thus, 4 -> 5; 6 -> 10, 22 -> 50, 56 -> 100,  120 -> 500, 540 -> 1000  ...
    function roundLimit(val) {
        let sign = Math.sign(val);
        let limit = Math.abs(Math.round(val));
        let digits = limit.toString().length;
        let multiplier = Math.pow(10, digits);

        if (limit > 10 && limit < multiplier / 4) {
            multiplier = multiplier / 4;
        } else if (limit < multiplier / 2) {
            multiplier = multiplier / 2;
        } else if (limit > 10 && limit < (multiplier * 3) / 4) {
            multiplier = (multiplier * 3) / 4;
        }

        limit = Math.ceil(limit / multiplier) * multiplier;
        return sign * limit;
    }

    function getDataLimits(data) {
        let limits = { xStart: 0, xEnd: 0 };
        if (data.sumNet < 0) {
            limits.xStart = roundLimit(data.sumNet);
        }
        limits.xEnd = roundLimit(Math.max(data.sumCredit, data.sumDebit));
        return limits;
    }

    function drawChart(data, chartType) {
        let systemSrc = getDataLimits(data);
        let systemDest = getDrawingCoordinates();

        let zeroPointPosition = translatePoint(0, systemSrc.xStart, systemSrc.xEnd, systemDest.xStart, systemDest.xEnd);

        let barCreditWidth =
            translatePoint(data.sumCredit, systemSrc.xStart, systemSrc.xEnd, systemDest.xStart, systemDest.xEnd) -
            zeroPointPosition;
        let barDebitWidth =
            translatePoint(data.sumDebit, systemSrc.xStart, systemSrc.xEnd, systemDest.xStart, systemDest.xEnd) -
            zeroPointPosition;
        let barNetWidth =
            translatePoint(Math.abs(data.sumNet), systemSrc.xStart, systemSrc.xEnd, systemDest.xStart, systemDest.xEnd) -
            zeroPointPosition;

        let netPosition = zeroPointPosition;
        if (barCreditWidth < 1 && barCreditWidth > -1) {
            barCreditWidth = 1;
        }
        let translatePosition = {};
        if (data.sumNet < 0) {
            translatePosition = { transform: 'translateX(' + zeroPointPosition + 'px)' };
            netPosition = zeroPointPosition - barNetWidth;
            $(chartPrefix + '.ruler .zero').css({ display: '' });
        } else {
            $(chartPrefix + '.ruler .zero').css({ display: 'none' });
        }

        let cssCredit = { ...translatePosition, width: barCreditWidth };
        let cssDebit = { ...translatePosition, width: barDebitWidth };
        let cssNet = { transform: 'translateX(' + netPosition + 'px)', width: barNetWidth };

        let chartPrefix = '.chart.' + chartType + ' ';

        $(chartPrefix + '.chart-bar.credit').css(cssCredit);
        $(chartPrefix + '.chart-bar.debit').css(cssDebit);
        $(chartPrefix + '.chart-bar.net').css(cssNet);

        $(chartPrefix + '.chart-label.credit strong').text(data.sumCredit);
        $(chartPrefix + '.chart-label.debit strong').text(data.sumDebit);
        $(chartPrefix + '.chart-label.net strong').text(data.sumNet);

        $(chartPrefix + '.ruler span.start')
            .css({ left: systemDest.xStart - 10 })
            .text(systemSrc.xStart);
        $(chartPrefix + '.ruler em.start').css({ left: systemDest.xStart });
        $(chartPrefix + '.ruler span.zero').css({ left: zeroPointPosition - 10 });
        $(chartPrefix + '.ruler em.zero').css({ left: zeroPointPosition });
        $(chartPrefix + '.ruler span.end')
            .css({ left: systemDest.xEnd - 20 })
            .text(systemSrc.xEnd);
        $(chartPrefix + '.ruler em.end').css({ left: systemDest.xEnd });
    }

    // Handle form
    let initializeForm = function () {
        initializeFilters();
        drawChart(dailySums, 'daily');
        drawChart(monthlySums, 'monthly');
    };

    // Public functions
    return {
        // Initialization
        init: function () {
            initializeForm();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    function i18nCallback() {
        KTStatisticsList.init();
    }
    I18NextTranslate({
        lng: _SELECTED_LANGUAGE,
        translationPath: _ASSET_PATH + 'lang/',
        callback: i18nCallback,
    });
});
