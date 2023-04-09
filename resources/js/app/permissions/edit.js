'use strict';

let strUtil;
// Class definition
let KTPermissionEdit = (function () {
    // Elements
    let formPermissionSave;

    let submitButtonPermissionSave;

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

    function handleValidationPermissionSaveError(frm, validator) {
        submitButtonPermissionSave.removeAttribute('data-kt-indicator');
        submitButtonPermissionSave.disabled = false;

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

    function initPermissionSaveValidation() {
        let rules = {
            name: 'required',
            title: 'required',
            slug: 'required',
        };
        let messages = {
            name: i18n.t('permission.NameRequired'),
            slug: i18n.t('permission.SlugRequired'),
        };
        $(formPermissionSave).validate({
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
                handleValidationPermissionSaveError(frm, validator);
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
        submitButtonPermissionSave.addEventListener('click', function (e) {
            e.preventDefault();
            // Show loading indication
            submitButtonPermissionSave.setAttribute('data-kt-indicator', 'on');
            // Disable button to avoid multiple click
            submitButtonPermissionSave.disabled = true;

            if ($(formPermissionSave).valid()) {
                formPermissionSave.submit();
            }
        });

        $('#name').on('blur', function () {
            strUtil.SetUrlKey('name', 'slug', false);
        });
        $('.regenerate-slug').on('click', function () {
            strUtil.SetUrlKey('name', 'slug', true);
        });
    }

    // Handle form
    let handleForm = function (e) {
        initPermissionSaveValidation();
        initActions();
    };

    // Public functions
    return {
        // Initialization
        init: function () {
            formPermissionSave = document.querySelector('#edit_form');
            submitButtonPermissionSave = document.querySelector('#edit_submit');
            editId = document.querySelector('#edit_id').value;

            handleForm();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    strUtil = new StringUtils();
    function i18nCallback() {
        KTPermissionEdit.init();
    }
    I18NextTranslate({
        lng: _SELECTED_LANGUAGE,
        translationPath: _ASSET_PATH + 'lang/',
        callback: i18nCallback,
    });
});
