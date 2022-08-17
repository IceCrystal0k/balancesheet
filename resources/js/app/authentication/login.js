"use strict";

// Class definition
var KTSigninGeneral = (function () {
    // Elements
    var form;
    var submitButton;

    function handleValidationError(frm, validator) {
        // Show error popup. For more info check the plugin's official documentation: https://sweetalert2.github.io/
        $("html, body").animate(
            {
                scrollTop: $(validator.errorList[0].element).offset().top,
            },
            1000
        );

        // Hide loading indication
        submitButton.removeAttribute("data-kt-indicator");
        // Enable button
        submitButton.disabled = false;

        Swal.fire({
            text: i18n.t("ErrorsDetected"),
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: i18n.t("ConfirmButtonText"),
            customClass: {
                confirmButton: "btn btn-primary",
            },
        });
    }

    function handleValidationSuccess() {
        return false;
    }

    function handleValidationBeforeSubmit() {
        return true;
    }

    function initValidation() {
        $(form).validate({
            rules: {
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                    minlength: 3,
                },
            },
            // Specify validation error messages
            messages: {
                password: {
                    required: i18n.t("auth.PasswordRequired"),
                    minlength: i18n.t("auth.PasswordMinLength", { length: 3 })
                        .res,
                },
                email: {
                    required: i18n.t("auth.EmailRequired"),
                    email: i18n.t("auth.EmailInvalid"),
                },
            },
            // Make sure the form is submitted to the destination defined
            // in the "action" attribute of the form when valid
            submitHandler: function (frm) {
                if (handleValidationBeforeSubmit()) {
                    frm.submit();
                }
            },
            invalidHandler: function (frm, validator) {
                handleValidationError(frm, validator);
            },
            success: function (label) {
                if (handleValidationSuccess()) return;
                else {
                    label.remove();
                }
            },
        });
    }

    // Handle form
    var handleForm = function (e) {
        initValidation();

        // Handle form submit
        submitButton.addEventListener("click", function (e) {
            e.preventDefault();
            // Show loading indication
            submitButton.setAttribute("data-kt-indicator", "on");
            // Disable button to avoid multiple click
            submitButton.disabled = true;
            localStorage.setItem("login-email", $("#email").val());

            if ($(form).valid()) {
                form.submit();
            }
        });
    };

    // Public functions
    return {
        // Initialization
        init: function () {
            form = document.querySelector("#kt_sign_in_form");
            submitButton = document.querySelector("#kt_sign_in_submit");

            handleForm();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    function i18nCallback() {
        console.log("completed translation");
        KTSigninGeneral.init();
    }
    I18NextTranslate({
        lng: _SELECTED_LANGUAGE,
        translationPath: _ASSET_PATH + "lang/",
        callback: i18nCallback,
    });
});
