'use strict';
import ProductService from '../services/product';

let strUtil;
// Class definition
let KTDailyBalanceEdit = (function () {
    // Elements
    let formSave;

    let submitButtonSave;

    let editId;

    function handleValidationSuccess(label) {
        return false;
    }

    function handleValidationBeforeSubmit(frm) {
        return false;
    }

    function handleErrorPlacement(error, element) {
        // for checkbox, insert error after label
        if (element.get(0).type === 'checkbox') {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
        return true;
    }

    function handleValidationSaveError(frm, validator) {
        submitButtonSave.removeAttribute('data-kt-indicator');
        submitButtonSave.disabled = false;

        $('html, body').animate(
            {
                scrollTop: $(validator.errorList[0].element).offset().top - 150,
            },
            1000
        );

        Swal.fire({
            text: i18n.t('ErrorsDetected'),
            icon: 'error',
            buttonsStyling: false,
            confirmButtonText: i18n.t('ConfirmButtonText'),
            customClass: {
                confirmButton: 'btn btn-primary',
            },
        });
    }

    function initSaveValidation() {
        jQuery.validator.addMethod(
            'validDate',
            function (value, element) {
                return this.optional(element) || moment(value, userDateFormat).isValid();
            },
            'You must enter a valid date'
        );

        let rules = {
            product_name: 'required',
            date_added: {
                required: true,
                validDate: true,
            },
            type_id: {
                required: true,
                integer: true,
                range: [1, 2],
            },
            target_id: {
                required: true,
                integer: true,
            },
            amount: {
                required: true,
                number: true,
                min: Number.MIN_VALUE,
            },
            unit_price: {
                required: true,
                number: true,
                min: Number.MIN_VALUE,
            },
        };
        let messages = {
            product_name: i18n.t('balancesheet.ProductRequired'),
            date_added: {
                required: i18n.t('balancesheet.DateRequired'),
                validDate: i18n.t('balancesheet.ValidDateRequired', { format: userDateFormat }),
            },
            type_id: {
                required: i18n.t('balancesheet.TypeRequired'),
                integer: i18n.t('balancesheet.TypeValidationInteger'),
                range: i18n.t('balancesheet.TypeValidationRange'),
            },
            target_id: {
                required: i18n.t('balancesheet.TargetRequired'),
                integer: i18n.t('balancesheet.TargetValidationInteger'),
            },
            amount: {
                required: i18n.t('balancesheet.AmountRequired'),
                number: i18n.t('balancesheet.AmountValidationNumber'),
                min: i18n.t('balancesheet.AmountValidationMin'),
            },
            unit_price: {
                required: i18n.t('balancesheet.UnitPriceRequired'),
                number: i18n.t('balancesheet.UnitPriceValidationNumber'),
                min: i18n.t('balancesheet.UnitPriceValidationMin'),
            },
        };
        $(formSave).validate({
            rules,
            // Specify validation error messages
            messages,
            // Make sure the form is submitted to the destination defined
            // in the "action" attribute of the form when valid
            submitHandler: function (frm) {
                if (handleValidationBeforeSubmit(frm)) {
                    return;
                } else {
                    frm.submit();
                }
            },
            invalidHandler: function (frm, validator) {
                handleValidationSaveError(frm, validator);
            },
            success: function (label) {
                if (handleValidationSuccess(label)) {
                    return;
                } else {
                    label.remove();
                }
            },
            errorPlacement: function (error, element) {
                if (handleErrorPlacement(error, element)) {
                    return;
                } else {
                    error.insertAfter(element);
                }
            },
        });
    }

    function initActions() {
        submitButtonSave.addEventListener('click', function (e) {
            e.preventDefault();
            // Show loading indication
            submitButtonSave.setAttribute('data-kt-indicator', 'on');
            // Disable button to avoid multiple click
            submitButtonSave.disabled = true;

            if ($(formSave).valid()) {
                formSave.submit();
            }
        });
        ProductService.init({ selector: '#product_name' });

        let rangePickerOptions = {
            singleDatePicker: true,
            minYear: 2022,
            timePicker: false,
            autoApply: true,
            locale: {
                format: userDateFormat,
            },
        };
        $('#date_added').daterangepicker(rangePickerOptions);
    }

    // Handle form
    let handleForm = function (e) {
        initSaveValidation();
        initActions();
    };

    // Public functions
    return {
        // Initialization
        init: function () {
            formSave = document.querySelector('#edit_form');
            submitButtonSave = document.querySelector('#edit_submit');
            editId = document.querySelector('#edit_id').value;

            handleForm();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    strUtil = new StringUtils();
    function i18nCallback() {
        KTDailyBalanceEdit.init();
    }
    I18NextTranslate({
        lng: _SELECTED_LANGUAGE,
        translationPath: _ASSET_PATH + 'lang/',
        callback: i18nCallback,
    });
});
