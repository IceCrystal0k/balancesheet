'use strict';
import ProductService from '../services/product';

// Class definition
let KTDailyBalanceList = (function () {
    // Elements
    function initializeTable() {
        const tableId = 'daily-balance-table';
        let tableRoute = $('#' + tableId).data('route');
        let table = $('#' + tableId).DataTable({
            processing: true,
            serverSide: true,
            dom: 'lrtip',
            iDisplayLength: 25,
            info: false,
            ajax: tableRoute,
            order: [2, 'desc'],
            columns: [
                {
                    data: 'select_row',
                    name: 'select_row',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'id',
                    name: 'id',
                },
                {
                    data: 'date_added',
                    name: 'date_added',
                },
                {
                    data: 'type_name',
                    name: 'type_name',
                },
                {
                    data: 'type_id',
                    name: 'type_id',
                    visible: false,
                },
                {
                    data: 'product_name',
                    name: 'product_name',
                },
                {
                    data: 'target_name',
                    name: 'target_name',
                },
                {
                    data: 'target_id',
                    name: 'target_id',
                    visible: false,
                },
                {
                    data: 'amount',
                    name: 'amount',
                },
                {
                    data: 'unit_price',
                    name: 'unit_price',
                },
                {
                    data: 'price',
                    name: 'price',
                },
                {
                    data: 'actions',
                    name: 'actions',
                    sortable: false,
                    searchable: false,
                },
            ],
        });
        table.on('draw', (ev, response) => {
            // enable menus for actions column
            KTMenu.createInstances();

            // make the Show Entries a select 2, to look nice
            $('select[name="' + tableId + '_length"]').select2({
                minimumResultsForSearch: 100, // don't show the search box
            });
            $('.dataTables_length .select2-selection--single').addClass('form-select form-select-solid');
            if (response && response.json) {
                $('#totalCredit').text(response.json.sumCredit);
                $('#totalDebit').text(response.json.sumDebit);
                $('#totalNet').text(response.json.sumNet);
            }
        });

        return table;
    }

    function confirmAction(ev, confirmText) {
        ev.preventDefault();
        let routeUrl = $(ev.target).data('route');
        Swal.fire({
            text: confirmText,
            icon: 'warning',
            buttonsStyling: false,
            confirmButtonText: i18n.t('ConfirmDeleteText'),
            showCancelButton: true,
            customClass: {
                confirmButton: 'btn btn-warning',
                cancelButton: 'btn btn-light',
            },
        }).then((result) => {
            if (result.isConfirmed) {
                $('#form-delete').attr('action', routeUrl);
                $('#form-delete').trigger('submit');
            }
        });
    }

    function initializeTableActions() {
        $('.table').on('click', '.item-delete', function (ev) {
            confirmAction(ev, i18n.t('ConfirmDeleteItem'));
        });
    }

    function getFilters() {
        return {
            product: $('#filterProduct').val(),
            price: JSON.stringify({ min: $('#filterPriceMin').val(), max: $('#filterPriceMax').val() }),
            date_added: JSON.stringify({ min: $('#filterDateMin').val(), max: $('#filterDateMax').val() }),
            target: $('#filterTarget').val(),
            type: $('#filterBalanceType').val(),
        };
    }

    function applyTableFilters(table) {
        let filters = getFilters();
        table.column('product_name:name').search(filters.product);
        table.column('price:name').search(filters.price);
        table.column('date_added:name').search(filters.date_added);
        table.column('target_id:name').search(filters.target);
        table.column('type_id:name').search(filters.type);

        table.draw();
    }

    function initializeTableFilters(table) {
        $('#listSearch').on('search', function (ev) {
            table.search($('#listSearch').val()).draw();
        });
        // the search event does the same thing, so this is no longer used
        // $('#listSearch').on('keypress', function (ev) {
        //     if (ev.key == 'Enter') {
        //         table.search($('#listSearch').val()).draw();
        //         return false;
        //     }
        //     return true;
        // });
        $('#tableGlobalSearch').on('click', function () {
            table.search($('#listSearch').val()).draw();
        });

        $('#toolbarFilter').on('click', 'button[type=submit]', function () {
            applyTableFilters(table);
        });

        $('#toolbarFilter').on('click', 'button[type=reset]', function () {
            $('#filterProduct').val('');
            $('#filterPriceMin').val('');
            $('#filterPriceMax').val('');
            $('#filterDateAddedMin').val('').trigger('change');
            $('#filterDateAddedMax').val('').trigger('change');
            $('#filterTarget').val('').trigger('change');
            $('#filterBalanceType').val('').trigger('change');

            table.columns().search('');
            table.draw();
        });

        let rangePickerOptions = {
            singleDatePicker: true,
            minYear: 2022,
            timePicker: false,
            autoApply: true,
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

    function initializeExport() {
        $('#export_cancel').on('click', function () {
            $('#export_modal').modal('hide');
        });
        $('#export_close').on('click', function () {
            $('#export_modal').modal('hide');
        });

        let exportForm = document.querySelector('#export_form');
        let exportButton = document.querySelector('#export_submit');
        exportButton.addEventListener('click', function (e) {
            exportButton.setAttribute('data-kt-indicator', 'on');
            exportButton.disabled = true;
            applyExportFilters();
            exportForm.submit();
            // just simulate download; see billing/users/list.js to see how to download file using ajax
            setTimeout(() => {
                exportButton.removeAttribute('data-kt-indicator');
                exportButton.disabled = false;
            }, 1500);
        });
    }

    function applyExportFilters() {
        let filters = getFilters();
        $('#exportFilters').val(JSON.stringify(filters));
    }

    // Handle form
    let initializeForm = function () {
        let table = initializeTable();
        initializeTableActions();
        initializeTableFilters(table);
        initializeExport();
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
        KTDailyBalanceList.init();
    }
    I18NextTranslate({
        lng: _SELECTED_LANGUAGE,
        translationPath: _ASSET_PATH + 'lang/',
        callback: i18nCallback,
    });
});
