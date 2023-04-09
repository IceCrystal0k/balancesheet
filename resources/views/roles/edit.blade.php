@extends('layouts.main')
@section('styles')
<link href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid">
    @include('partials.page.toolbar')
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid">
        <!--begin::Container-->
        <div id="kt_content_container" class="container">
            <!--begin::Basic info-->
            <div class="card mb-5 mb-xl-10">
                <!--begin::Card header-->
                <div class="card-header cursor-pointer">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">{{ $page->name }}</h3>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--begin::Card header-->
                <!--begin::Content-->
                <div class="collapse show">
                    <!--begin::Form-->
                    @include('errors.error')
                    @include('errors.success')
                    <form id="edit_form" class="form" method="post" action="{{ $page->routeSave }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="edit_id" id="edit_id" value="{{ $data->id }}" />
                        <!--begin::Card body-->
                        <div class="card-body border-top p-9">
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-3 col-form-label required fw-bold fs-6">{{ __('tables.Name') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-9">
                                    <input type="text" name="name" id="name"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                        placeholder="{{ __('tables.Name') }}" value="{{ $data->name }}" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-3 col-form-label fw-bold fs-6">
                                    <span class="required">{{ __('tables.Slug') }}</span>
                                    <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip"
                                        title="{{ __($page->translationPrefix.'SlugTooltip') }}"></i>
                                    <i class="bi bi-arrow-clockwise ms-1 cursor-pointer regenerate-slug"
                                        data-bs-toggle="tooltip"
                                        title="{{ __($page->translationPrefix.'SlugRefreshTooltip') }}"></i>
                                </label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-9 fv-row">
                                    <input type="text" name="slug" id="slug"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="{{ __('tables.Slug') }}" value="{{ $data->slug }}" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Card body-->
                        <!--begin::Actions-->
                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <button type="submit" class="btn btn-primary"
                                id="edit_submit">{{ __('general.SaveChanges') }}</button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Basic info-->

        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>

<!--end::Content-->

@section('vendor_js_files')
<script src="{{ asset('vendors/i18next/i18next.min.js') }}"></script>
<script src="{{ asset('vendors/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('vendors/jquery-validate/jquery.validate.min.js') }}"></script>
<script src="{{ asset('vendors/jquery-validate/additional-methods.min.js') }}"></script>
@endsection

@section('page_js_files')
<script src="{{ asset('js/custom/strings/strings.js') }}"></script>
<script src="{{ asset('js/app/permissions/edit.js') }}" defer="defer"></script>
@endsection

@endsection
