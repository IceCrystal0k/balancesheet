KTUtil.onDOMContentLoaded(function() {
    function handleAlertDismiss() {
        $(document).on('click', '.alert-dismissible button[data-bs-dismiss="alert"]', function(ev) {
            ev.target.closest('.alert-dismissible').remove();
        });
    }
    handleAlertDismiss();
});
