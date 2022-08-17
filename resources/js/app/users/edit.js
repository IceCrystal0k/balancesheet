'use strict';

// Class definition
let KTUserEdit = (function () {
    // Elements
    let formEmailChange;
    let formPasswordChange;
    let formUserSave;

    let submitButtonEmailChange;
    let submitButtonPasswordChange;
    let submitButtonUserSave;

    let editId;

    function handleEmailChangeValidationError(frm, validator) {
        // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
        $('html, body').animate(
            {
                scrollTop: $(validator.errorList[0].element).offset().top - 150,
            },
            1000
        );

        submitButtonEmailChange.removeAttribute('data-kt-indicator');
        submitButtonEmailChange.disabled = false;

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
                new_password: {
                    required: i18n.t('user.NewPasswordRequired'),
                    minlength: i18n.t('user.NewPasswordMinLength', {
                        length: 8,
                    }).res,
                },
                password_confirmation: {
                    equalTo: i18n.t('user.ConfirmPasswordEqualToPassword'),
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

    function handleValidationUserSaveError(frm, validator) {
        submitButtonUserSave.removeAttribute('data-kt-indicator');
        submitButtonUserSave.disabled = false;

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

    function initUserSaveValidation() {
        let rules = {
            first_name: 'required',
            last_name: 'required',
            country: 'required',
            language: 'required',
        };
        let messages = {
            first_name: i18n.t('auth.FirstNameRequired'),
            last_name: i18n.t('auth.LastNameRequired'),
            country: i18n.t('account.CountryRequired'),
            language: i18n.t('account.LanguageRequired'),
        };
        if (!editId) {
            rules.email = { required: true, email: true };
            rules.password = { required: true, minlength: 8 };
            messages.email = {
                required: i18n.t('auth.EmailRequired'),
                email: i18n.t('auth.EmailInvalid'),
            };
            messages.password = {
                required: i18n.t('auth.PasswordRequired'),
                minlength: i18n.t('auth.PasswordMinLength', { length: 8 }).res,
            };
        }
        $(formUserSave).validate({
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
                handleValidationUserSaveError(frm, validator);
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

    function initEmailChangeValidation() {
        $(formEmailChange).validate({
            rules: {
                new_email: {
                    required: true,
                    email: true,
                    minlength: 5,
                },
            },
            // Specify validation error messages
            messages: {
                new_email: {
                    required: i18n.t('user.NewEmailRequired'),
                    minlength: i18n.t('user.NewEmailMinLength', {
                        length: 5,
                    }).res,
                    email: i18n.t('user.NewEmailValid'),
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
                handleEmailChangeValidationError(frm, validator);
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
        $('#password_button').on('click', 'button', function () {
            $('#password_edit').removeClass('d-none');
            $('#password_button').addClass('d-none');
        });
        $('#password_cancel').on('click', function () {
            $('#password_edit').addClass('d-none');
            $('#password_button').removeClass('d-none');
        });

        $('#email_button').on('click', 'button', function () {
            $('#email_edit').removeClass('d-none');
            $('#email_button').addClass('d-none');
        });
        $('#email_cancel').on('click', function () {
            $('#email_edit').addClass('d-none');
            $('#email_button').removeClass('d-none');
        });

        let imageInputElement = document.querySelector('.image-input');
        let imageInput = KTImageInput.getInstance(imageInputElement);
        imageInput.on('kt.imageinput.change', function () {
            $('#avatar_remove').val('0');
        });

        if (editId) {
            // Handle password change submit
            submitButtonPasswordChange.addEventListener('click', function (e) {
                e.preventDefault();
                submitButtonPasswordChange.setAttribute('data-kt-indicator', 'on');
                submitButtonPasswordChange.disabled = true;

                if ($(formPasswordChange).valid()) {
                    formPasswordChange.submit();
                }
            });

            // Handle email change submit
            submitButtonEmailChange.addEventListener('click', function (e) {
                e.preventDefault();
                submitButtonEmailChange.setAttribute('data-kt-indicator', 'on');
                submitButtonEmailChange.disabled = true;

                if ($(formEmailChange).valid()) {
                    formEmailChange.submit();
                }
            });
        }

        submitButtonUserSave.addEventListener('click', function (e) {
            e.preventDefault();
            // Show loading indication
            submitButtonUserSave.setAttribute('data-kt-indicator', 'on');
            // Disable button to avoid multiple click
            submitButtonUserSave.disabled = true;

            if ($(formUserSave).valid()) {
                formUserSave.submit();
            }
        });
    }

    // Handle form
    let handleForm = function (e) {
        initEmailChangeValidation();
        initPasswordChangeValidation();
        initUserSaveValidation();
        initActions();
    };

    // Public functions
    return {
        // Initialization
        init: function () {
            formUserSave = document.querySelector('#edit_form');
            submitButtonUserSave = document.querySelector('#edit_submit');
            editId = document.querySelector('#edit_id').value;

            if (editId) {
                submitButtonPasswordChange = document.querySelector('#password_submit');
                formPasswordChange = document.querySelector('#change_password');

                submitButtonEmailChange = document.querySelector('#email_submit');
                formEmailChange = document.querySelector('#change_email');
            }

            handleForm();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    function i18nCallback() {
        KTUserEdit.init();
    }
    I18NextTranslate({
        lng: _SELECTED_LANGUAGE,
        translationPath: _ASSET_PATH + 'lang/',
        callback: i18nCallback,
    });
});
