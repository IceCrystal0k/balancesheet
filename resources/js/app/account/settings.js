'use strict';

// Class definition
let KTSettings = (function () {
    // Elements
    let formAccountDelete;
    let formPasswordChange;
    let formProfileUpdate;

    let submitButtonAccountDelete;
    let submitButtonPasswordChange;
    let submitButtonProfileUpdate;

    function handlePasswordChangeValidationError(frm, validator) {
        // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
        $('html, body').animate(
            {
                scrollTop: $(validator.errorList[0].element).offset().top - 150,
            },
            1000
        );

        submitButtonPasswordChange.removeAttribute('data-kt-indicator');
        submitButtonPasswordChange.disabled = false;

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

    function initPasswordChangeValidation() {
        $(formPasswordChange).validate({
            rules: {
                current_password: {
                    required: true,
                },
                new_password: {
                    required: true,
                    minlength: 8,
                },
                password_confirmation: {
                    equalTo: '#new_password',
                },
            },
            // Specify validation error messages
            messages: {
                current_password: {
                    required: i18n.t('account.CurrentPasswordRequired'),
                },
                new_password: {
                    required: i18n.t('account.NewPasswordRequired'),
                    minlength: i18n.t('account.NewPasswordMinLength', { length: 8 }).res,
                },
                password_confirmation: {
                    equalTo: i18n.t('account.ConfirmPasswordEqualToPassword'),
                },
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
                handlePasswordChangeValidationError(frm, validator);
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

    function handleValidationDeleteBeforeSubmit(frm) {
        Swal.fire({
            text: i18n.t('account.AreYouSureYouWantToDeleteAccount'),
            icon: 'warning',
            buttonsStyling: false,
            showCancelButton: true,
            confirmButtonText: i18n.t('Yes'),
            cancelButtonText: i18n.t('No'),
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-secondary',
            },
        }).then((result) => {
            if (result.isConfirmed) {
                frm.submit();
            } else {
                submitButtonAccountDelete.removeAttribute('data-kt-indicator');
                submitButtonAccountDelete.disabled = false;
            }
        });
        return true;
    }

    function initAccountDeleteValidation() {
        $(formAccountDelete).validate({
            rules: {
                confirm_delete: {
                    required: true,
                },
            },
            // Specify validation error messages
            messages: {
                confirm_delete: {
                    required: i18n.t('account.ConfirmAccountDeletion'),
                },
            },
            // Make sure the form is submitted to the destination defined
            // in the "action" attribute of the form when valid
            submitHandler: function (frm) {
                // submit handler is not called when submit button.click event is added
                console.log('submit handler triggers');
                if (handleValidationDeleteBeforeSubmit(frm)) {
                    return;
                } else {
                    frm.submit();
                }
            },
            invalidHandler: function (frm, validator) {
                handleValidationDeleteError(frm, validator);
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

    function handleValidationDeleteError(frm, validator) {
        submitButtonAccountDelete.removeAttribute('data-kt-indicator');
        submitButtonAccountDelete.disabled = false;

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

    function handleValidationProfileUpdateError(frm, validator) {
        submitButtonProfileUpdate.removeAttribute('data-kt-indicator');
        submitButtonProfileUpdate.disabled = false;

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

    function initProfileUpdateValidation() {
        $(formProfileUpdate).validate({
            rules: {
                first_name: 'required',
                last_name: 'required',
                country: 'required',
                language: 'required',
            },
            // Specify validation error messages
            messages: {
                first_name: i18n.t('auth.FirstNameRequired'),
                last_name: i18n.t('auth.LastNameRequired'),
                country: i18n.t('account.CountryRequired'),
                language: i18n.t('account.LanguageRequired'),
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
                handleValidationProfileUpdateError(frm, validator);
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
        $('#signin_password_button').on('click', 'button', function () {
            $('#signin_password_edit').removeClass('d-none');
            $('#signin_password_button').addClass('d-none');
        });
        $('#password_cancel').on('click', function () {
            $('#signin_password_edit').addClass('d-none');
            $('#signin_password_button').removeClass('d-none');
        });

        let imageInputElement = document.querySelector('.image-input');
        let imageInput = KTImageInput.getInstance(imageInputElement);
        imageInput.on('kt.imageinput.change', function () {
            $('#avatar_remove').val('0');
        });

        submitButtonProfileUpdate.addEventListener('click', function (e) {
            e.preventDefault();
            // Show loading indication
            submitButtonProfileUpdate.setAttribute('data-kt-indicator', 'on');
            // Disable button to avoid multiple click
            submitButtonProfileUpdate.disabled = true;

            if ($(formProfileUpdate).valid()) {
                formProfileUpdate.submit();
            }
        });

        // Handle password change submit
        submitButtonPasswordChange.addEventListener('click', function (e) {
            e.preventDefault();
            submitButtonPasswordChange.setAttribute('data-kt-indicator', 'on');
            submitButtonPasswordChange.disabled = true;

            if ($(formPasswordChange).valid()) {
                formPasswordChange.submit();
            }
        });

        // Handle account delete submit
        submitButtonAccountDelete.addEventListener('click', function (e) {
            e.preventDefault();
            submitButtonAccountDelete.setAttribute('data-kt-indicator', 'on');
            submitButtonAccountDelete.disabled = true;

            if ($(formAccountDelete).valid()) {
                handleValidationDeleteBeforeSubmit(formAccountDelete);
                console.log('is valid');
            }
        });
    }

    // Handle form
    let handleForm = function (e) {
        initPasswordChangeValidation();
        initAccountDeleteValidation();
        initProfileUpdateValidation();
        initActions();
    };

    // Public functions
    return {
        // Initialization
        init: function () {
            formAccountDelete = document.querySelector('#account_delete_form');
            formPasswordChange = document.querySelector('#signin_change_password');
            formProfileUpdate = document.querySelector('#account_settings_form');

            submitButtonAccountDelete = document.querySelector('#account_delete_submit');
            submitButtonPasswordChange = document.querySelector('#password_submit');
            submitButtonProfileUpdate = document.querySelector('#account_settings_submit');

            handleForm();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    function i18nCallback() {
        KTSettings.init();
    }
    I18NextTranslate({
        lng: _SELECTED_LANGUAGE,
        translationPath: _ASSET_PATH + 'lang/',
        callback: i18nCallback,
    });
});
