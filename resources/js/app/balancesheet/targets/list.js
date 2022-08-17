'use strict';

// Class definition
let KTTargetList = (function () {
    // Elements
    function initializeTable() {
        const tableId = 'targets-table';
        let tableRoute = $('#' + tableId).data('route');
        let table = $('#' + tableId).DataTable({
            processing: true,
            serverSide: true,
            dom: 'lrtip',
            iDisplayLength: 25,
            info: false,
            ajax: tableRoute,
            order: [1, 'asc'],
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
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'actions',
                    name: 'actions',
                    sortable: false,
                    searchable: false,
                },
            ],
        });
        table.on('draw', () => {
            // enable menus for actions column
            KTMenu.createInstances();

            // make the Show Entries a select 2, to look nice
            $('select[name="' + tableId + '_length"]').select2({
                minimumResultsForSearch: 100, // don't show the search box
            });
            $('.dataTables_length .select2-selection--single').addClass('form-select form-select-solid');
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
            confirmAction(ev, i18n.t('balancesheet.target.ConfirmDelete'));
        });
    }

    function initializeTableFilters(table) {
        $('#listSearch').on('search', function (ev) {
            table.search($('#listSearch').val()).draw();
        });
        $('#tableGlobalSearch').on('click', function () {
            table.search($('#listSearch').val()).draw();
        });

        $('#toolbarFilter').on('click', 'button[type=submit]', function () {
            let nameVal = $('#filterName').val();
            table.column('name:name').search(nameVal);

            table.draw();
        });

        $('#toolbarFilter').on('click', 'button[type=reset]', function () {
            $('#filterName').val('');

            table.columns().search('');
            table.draw();
        });
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
            exportForm.submit();
            // just simulate download; see billing/users/list.js to see how to download file using ajax
            setTimeout(() => {
                exportButton.removeAttribute('data-kt-indicator');
                exportButton.disabled = false;
            }, 1500);
        });
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
        KTTargetList.init();
    }
    I18NextTranslate({
        lng: _SELECTED_LANGUAGE,
        translationPath: _ASSET_PATH + 'lang/',
        callback: i18nCallback,
    });
});
