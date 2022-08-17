<!--begin::Card header Filter-->
<div class="card-header border-0 pt-6">
    @if (isset($hideGlobalFilter))
    <div class="card-title"></div>
    @else
    <!--begin::Card title-->
    <div class="card-title">
        <!--begin::Search-->
        <div class="d-flex align-items-center position-relative my-1">
            <!--begin::Svg Icon | path: icons/duotone/General/Search.svg-->
            <span class="svg-icon svg-icon-1 position-absolute ms-6 cursor-pointer" id="tableGlobalSearch">
                @svg('media/theme/icons/duotone/general/search.svg')
            </span>
            <!--end::Svg Icon-->
            <input type="search" class="form-control form-control-solid w-250px ps-15" id="listSearch"
                placeholder="{{$globalSearchPlaceholder}}" />
        </div>
        <!--end::Search-->
    </div>
    <!--begin::Card title-->
    @endif
    <!--begin::Card toolbar-->
    <div class="card-toolbar">
        <!--begin::Toolbar-->
        <div class="d-flex justify-content-end">
            <!--begin::Filter-->
            <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click"
                data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                <!--begin::Svg Icon | path: icons/duotone/Text/Filter.svg-->
                <span class="svg-icon svg-icon-2">
                    @svg('media/theme/icons/duotone/text/filter.svg')
                </span>
                <!--end::Svg Icon-->{{ __('tables.Filter') }}
            </button>
            <!--begin::Menu 1-->
            <div class="menu menu-sub menu-sub-dropdown w-400px w-md-425px" data-kt-menu="true" id="toolbarFilter">
                <!--begin::Header-->
                <div class="px-7 py-5">
                    <div class="fs-4 text-dark fw-bolder">{{ __('tables.FilterOptions') }}</div>
                </div>
                <!--end::Header-->
                <!--begin::Separator-->
                <div class="separator border-gray-200"></div>
                <!--end::Separator-->
                <!--begin::Content-->
                <div class="px-7 py-5 hover-scroll" style="max-height: 55vh">
                    @yield('filter_content')

                    <!--begin::Actions-->
                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-light btn-active-light-primary me-2"
                            data-kt-menu-dismiss="true">{{ __('general.Reset') }}</button>
                        <button type="submit" class="btn btn-primary"
                            data-kt-menu-dismiss="true">{{ __('general.Apply') }}</button>
                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Menu 1-->
            <!--end::Filter-->
            @if (!isset($hideExport))
            <!--begin::Export-->
            <button type="button" class="btn btn-light-primary me-3" data-bs-toggle="modal"
                data-bs-target="#export_modal">
                <!--begin::Svg Icon | path: icons/duotone/Files/Export.svg-->
                <span class="svg-icon svg-icon-2">
                    @svg('media/theme/icons/duotone/files/export.svg')
                </span>
                <!--end::Svg Icon-->{{ __('general.Export') }}
            </button>
            <!--end::Export-->
            @endif
            @yield('extra_actions')
            @hasSection('custom_create')
            @yield('custom_create')
            @else
            <!--begin::Add bill-->
            <a class="btn btn-primary btn-create" href="{{$page->routeCreate}}">
                <!--begin::Svg Icon | path: icons/duotone/Navigation/Plus.svg-->
                <span class="svg-icon svg-icon-2">
                    @svg('media/theme/icons/duotone/navigation/plus.svg')
                </span>
                <!--end::Svg Icon-->{{$createNewLabel}}
            </a>
            <!--end::Add bills-->
            @endif
        </div>
        <!--end::Toolbar-->
        <!--begin::Group actions-->
        <div class="d-flex justify-content-end align-items-center d-none">
            <div class="fw-bolder mx-5">
                <span class="me-2"></span>Selected
            </div>
            <button type="button" class="btn btn-danger">{{__('general.DeleteSelected')}}</button>
        </div>
        <!--end::Group actions-->
    </div>
    <!--end::Card toolbar-->
</div>
<!--end::Card header Filter-->
