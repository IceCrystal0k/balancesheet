@extends('layouts.main')
@section('styles')
<link href="{{ asset('vendors/datatables/datatables.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/datatables/plugins/dataTables.responsive.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid">
    @include('partials.page.toolbar', ['btn_create' => __($page->translationPrefix.'CreateNew')])
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid">
        <!--begin::Container-->
        <div id="kt_content_container" class="container">
            <!--begin::Card-->
            <div class="card">
                @include('errors.success')
                @include('errors.error')

                @include($page->viewPath.'.filter')

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle table-row-dashed fs-6 gy-5"
                            id="permissions-table" data-route="{{ route($page->routePath.'/list') }}">
                            <thead>
                                <tr>
                                    <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                data-kt-check-target="#permissions-table .form-check-input" value="1" />
                                        </div>
                                    </th>
                                    <th>{{ __('tables.Id') }}</th>
                                    <th>{{ __('tables.Name') }}</th>
                                    <th>{{ __('tables.Slug') }}</th>
                                    <th>{{ __('tables.Actions') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!--end::Card body-->
                @include('partials.form.delete_form')
                @include('partials.form.post_form')
            </div>
            <!--end::Card-->
            <!--begin::Modals-->
            @include($page->viewPath.'.export')
            <!--end::Modal - New Card-->
            <!--end::Modals-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->
@endsection

@section('vendor_js_files')
<script src="{{ asset('vendors/i18next/i18next.min.js') }}"></script>
<script src="{{ asset('vendors/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('vendors/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('vendors/datatables/plugins/dataTables.responsive.min.js') }}"></script>
@endsection

@section('page_js_files')
<script src="{{ asset('js/app/'.$page->viewPath.'/list.js') }}"></script>
@endsection
