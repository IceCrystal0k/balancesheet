'use strict';
/**
 * class which initialize an autocomplete for products
 */
// Class definition
var StatisticService = (function () {
    // Elements
    function initAjax(options) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });
    }

    function applyFilters(request, callback) {
        $.ajax({
            url: '/balancesheet/statistics/chart',
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            data: request,
            success: function (data) {
                callback(data, true);
            },
            error: function (data) {
                callback(data);
            },
        });
    }

    initAjax();

    // Public functions
    return {
        getChartData: function (request, callback) {
            applyFilters(request, callback);
        },
    };
})();

export default StatisticService;
