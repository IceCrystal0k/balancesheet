"use strict";

// Class definition
var KTVerifyEmail = (function () {
    // Elements
    var form;
    var submitButton;

    // Handle form
    var handleForm = function (e) {
        // Handle form submit
        submitButton.addEventListener("click", function (e) {
            e.preventDefault();
            // Show loading indication
            submitButton.setAttribute("data-kt-indicator", "on");
            // Disable button to avoid multiple click
            submitButton.disabled = true;

            form.submit();
        });
    };

    // Public functions
    return {
        // Initialization
        init: function () {
            form = document.querySelector("#kt_verify_email_form");
            submitButton = document.querySelector("#kt_verify_email_submit");

            handleForm();
        },
    };
})();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTVerifyEmail.init();
});
