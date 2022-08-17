'use strict';

// Class definition
var KTRegister = (function () {
    // Elements
    var form;
    var submitButton;

    function handleValidationError(frm, validator) {
        // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
        $('html, body').animate(
            {
                scrollTop: $(validator.errorList[0].element).offset().top,
            },
            1000
        );

        // Hide loading indication
        submitButton.removeAttribute('data-kt-indicator');
        // Enable button
        submitButton.disabled = false;

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

    function initValidation() {
        $(form).validate({
            rules: {
                first_name: 'required',
                last_name: 'required',
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                    minlength: 8,
                },
                password_confirmation: {
                    equalTo: '#password',
                },
                agree_tc: 'required',
            },
            // Specify validation error messages
            messages: {
                first_name: i18n.t('auth.FirstNameRequired'),
                last_name: i18n.t('auth.LastNameRequired'),
                password: {
                    required: i18n.t('auth.PasswordRequired'),
                    minlength: i18n.t('auth.PasswordMinLength', { length: 8 }).res,
                },
                email: {
                    required: i18n.t('auth.EmailRequired'),
                    email: i18n.t('auth.EmailInvalid'),
                },
                password_confirmation: {
                    equalTo: i18n.t('auth.ConfirmPasswordEqualToPassword'),
                },
                agree_tc: i18n.t('auth.AgreeTermsAndConditions'),
            },
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
                handleValidationError(frm, validator);
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

    // Handle form
    var handleForm = function (e) {
        initValidation();

        // Handle form submit
        submitButton.addEventListener('click', function (e) {
            e.preventDefault();
            // Show loading indication
            submitButton.setAttribute('data-kt-indicator', 'on');
            // Disable button to avoid multiple click
            submitButton.disabled = true;

            if ($(form).valid()) {
                form.submit();
            }
        });
    };

    // Public functions
    return {
        // Initialization
        init: function () {
            form = document.querySelector('#kt_sign_up_form');
            submitButton = document.querySelector('#kt_sign_up_submit');

            handleForm();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    function i18nCallback() {
        KTRegister.init();
    }
    I18NextTranslate({
        lng: _SELECTED_LANGUAGE,
        translationPath: _ASSET_PATH + 'lang/',
        callback: i18nCallback,
    });
});
