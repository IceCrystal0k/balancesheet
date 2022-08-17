<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#101E3B" />
    @yield('meta_tags')
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Theme styles -->
    <link href="{{ asset('css/theme/plugins.dark.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/theme/style.dark.css') }}" rel="stylesheet" type="text/css" />
    <!-- /theme styles -->

    <!-- Global stylesheets -->
    <link href="{{ asset('css/app/app.css') }}" rel="stylesheet" type="text/css" />
    <!-- /global stylesheets -->

    <!-- Page styles -->
    @yield('styles')
    <!-- /page styles -->

    <link href="{{ asset('media/favicon.png') }}" rel="shortcut icon" />

    <!-- Header JS files -->
    @yield('javascript_header')
    <!-- /header JS files -->
</head>

<!--begin::Body-->

<body id="kt_body" class="bg-body">
    <!-- Page content -->
    <div class="d-flex flex-column flex-root">
        <!-- Main content -->
        <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
            style="background-image: url({{ asset('media/theme/illustrations/development-hd-dark.png') }}">
            @yield('content')
        </div>
        <!-- /main content -->
        <!--begin::Footer-->
        <div class="d-flex flex-center flex-column-auto p-10">
            <!--begin::Links-->
            <div class="d-flex align-items-center fw-bold fs-6">
                <a href="https://www.rebrander.ro"
                    class="text-muted text-hover-primary px-2">{{ __('footer.About') }}</a>
                <a href="https://www.rebranded.ro/contact"
                    class="text-muted text-hover-primary px-2">{{ __('footer.Contact') }}</a>
            </div>
            <!--end::Links-->
        </div>
        <!--end::Footer-->
    </div>
    <!-- /page content -->

    <script>
    let _ASSET_PATH = '{{ asset("")}}';
    let _SELECTED_LANGUAGE = '{{ app()->getLocale() }}';
    </script>

    <script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
    <!--JS Files-->
    @yield('vendor_js_files')

    <!-- Global JS Files (used by all pages) -->
    <script src="{{ asset('js/theme/scripts.js') }}"></script>
    <!-- /global JS Files -->

    <script src="{{ asset('js/app/app.js') }}"></script>

    <!-- Theme custom JS files -->
    @yield('theme_js_files')
    <!-- /theme custom JS files -->

    <!-- Page Custom JS (used by this page) -->
    @yield('page_js_files')
    <!-- /page Custom JS -->

    <!-- /js Files -->
</body>



</html>
