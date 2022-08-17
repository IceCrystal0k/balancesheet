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

    <!-- Page styles -->
    <link href="{{ asset('vendors/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    @yield('styles')
    <!-- /page styles -->

    <!-- Theme styles -->
    <link href="{{ asset('css/theme/plugins.dark.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/theme/style.dark.css') }}" rel="stylesheet" type="text/css" />
    <!-- /theme styles -->

    <!-- Global stylesheets -->
    <link href="{{ asset('css/app/app.css') }}" rel="stylesheet" type="text/css" />
    <!-- /global stylesheets -->

    <link href="{{ asset('media/favicon.png') }}" rel="shortcut icon" />

    <!-- Header JS files -->
    @yield('javascript_header')
    <!-- /header JS files -->
</head>

<!--begin::Body-->

<body id="kt_body"
    class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed"
    style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
    <!-- Page content -->
    <div class="d-flex flex-column flex-root" style="display: none">
        <!--begin::Aside-->
        @include('partials.page.side')
        <!--end::Aside-->
        <!-- Main content -->
        <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
            @include('partials.page.header')
            @yield('content')
            @include('partials.page.footer')
        </div>
        <!-- /main content -->
    </div>
    <!-- /page content -->

    <script>
    let _ASSET_PATH = '{{ asset("")}}';
    let _SELECTED_LANGUAGE = '{{ app()->getLocale() }}';
    </script>

    <script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendors/select2/select2.min.js') }}"></script>
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
