'use strict';

// Class definition
let KTUserList = (function () {
    // Elements
    function initializeTable() {
        const tableId = 'users-table';
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
                    data: 'full_name',
                    name: 'full_name',
                },
                {
                    data: 'email',
                    name: 'email',
                },
                {
                    data: 'role_name',
                    name: 'role_name',
                },
                {
                    data: 'role_id',
                    name: 'role_id',
                    visible: false,
                },
                {
                    data: 'updated_at',
                    name: 'updated_at',
                },
                {
                    data: 'google',
                    name: 'google_id',
                },
                {
                    data: 'facebook',
                    name: 'fb_id',
                },
                {
                    data: 'status_name',
                    name: 'status_name',
                },
                {
                    data: 'status',
                    name: 'status',
                    visible: false,
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
            confirmAction(ev, i18n.t('ConfirmDeleteItem'));
        });

        $('.table').on('click', '.item-remove', function (ev) {
            confirmAction(ev, i18n.t('ConfirmRemoveItem'));
        });

        $('.table').on('click', '.item-activate, .item-deactivate', function (ev) {
            ev.preventDefault();
            let routeUrl = $(ev.target).data('route');
            $('#form-post').attr('action', routeUrl);
            $('#form-post').trigger('submit');
        });
    }

    function initializeExport() {
        $('#export_daterange').daterangepicker({
            timePicker: true,
            startDate: moment().startOf('day').subtract(30, 'day'),
            endDate: moment().startOf('hour'),
            locale: {
                format: 'YYYY/MM/DD hh:mm',
            },
        });
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

    function initializeTableFilters(table) {
        $('#listSearch').on('search', function (ev) {
            table.search($('#listSearch').val()).draw();
        });
        $('#tableGlobalSearch').on('click', function () {
            table.search($('#listSearch').val()).draw();
        });

        $('#toolbarFilter').on('click', 'button[type=submit]', function () {
            let googleVal = $('#filterGoogle').val();
            table.column('google_id:name').search(googleVal);

            let facebookVal = $('#filterFacebook').val();
            table.column('fb_id:name').search(facebookVal);

            let nameVal = $('#filterName').val();
            table.column('full_name:name').search(nameVal);

            let emailVal = $('#filterEmail').val();
            table.column('email:name').search(emailVal);

            let statusList = $('#filterStatus input:checked')
                .map((index, item) => item.value)
                .get();
            table.column('status:name').search(statusList);

            let roleList = $('#filterRole input:checked')
                .map((index, item) => item.value)
                .get();
            table.column('role_id:name').search(roleList);

            table.draw();
        });

        $('#toolbarFilter').on('click', 'button[type=reset]', function () {
            $('#filterGoogle').val(null).trigger('change');
            $('#filterFacebook').val(null).trigger('change');
            $('#filterName').val('');
            $('#filterEmail').val('');
            $('#filterStatus input:checked').prop('checked', false);
            $('#filterRole input:checked').prop('checked', false);

            table.columns().search('');
            table.draw();
        });
    }

    // Handle form
    let handlePage = function () {
        let table = initializeTable();
        initializeTableActions();
        initializeTableFilters(table);
        initializeExport();
    };

    // Public functions
    return {
        // Initialization
        init: function () {
            handlePage();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    function i18nCallback() {
        KTUserList.init();
    }
    I18NextTranslate({
        lng: _SELECTED_LANGUAGE,
        translationPath: _ASSET_PATH + 'lang/',
        callback: i18nCallback,
    });
});
