<!--begin::Toolbar-->
<div class="toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
            data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
            class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <!--begin::Title-->
            <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">{{ $page->title }}</h1>
            <!--end::Title-->
            <!--begin::Separator-->
            <span class="h-20px border-gray-200 border-start mx-4"></span>
            <!--end::Separator-->
            {{ Breadcrumbs::render($breadcrumbPath, $page) }}
        </div>
        <!--end::Page title-->
        <!--begin::Actions-->
        <div class="d-flex align-items-center py-1">
            @if (isset($page->routeCreate))
            <!--begin::Button-->
            <a href="{{ $page->routeCreate }}" class="btn btn-sm btn-primary btn-create">{{ $btn_create }}</a>
            <!--end::Button-->
            @endif
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Container-->
</div>
<!--end::Toolbar-->
