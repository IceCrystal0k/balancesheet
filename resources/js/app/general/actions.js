KTUtil.onDOMContentLoaded(function () {
    function handleUserLogout() {
        $(document).on("click", "#btn-signout", function (ev) {
            $("#signout-form").submit();
        });
    }
    handleUserLogout();
});
