<!--begin::Footer-->
<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
    <!--begin::Container-->
    <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
        <!--begin::Copyright-->
        <div class="text-dark order-2 order-md-1">
            <span class="text-muted fw-bold me-1">&copy;{!!date('Y')!!}</span>
            <a href="{{ config('settings.copyrigth_link') }}" target="_blank"
                class="text-gray-800 text-hover-primary">{{__('footer.CopyrightAuthor')}}</a>
        </div>
        <!--end::Copyright-->
        <!--begin::Menu-->
        <ul class="menu menu-gray-600 menu-hover-primary fw-bold order-1">
            <li class="menu-item">
                <a href="https://rebrander.ro/about" target="_blank" class="menu-link px-2">{{__('footer.About')}}</a>
            </li>
            <li class="menu-item">
                <a href="https://rebrander.ro/support" target="_blank"
                    class="menu-link px-2">{{__('footer.Support')}}</a>
            </li>
        </ul>
        <!--end::Menu-->
    </div>
    <!--end::Container-->
</div>
<!--end::Footer-->
