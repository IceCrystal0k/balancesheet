@extends('layouts.main')
@section('styles')
<link href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/jquery-ui/jquery-ui.structure.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/jquery-ui/jquery-ui.theme.min.css') }}" rel="stylesheet" type="text/css" />

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
                                <label class="col-lg-3 col-form-label required fw-bold fs-6">{{ __('tables.Date') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-9">
                                    <input type="text" name="date_added" id="date_added" autocomplete="off"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                        placeholder="{{ __('tables.Date') }}" value="{{ $data->date_added }}" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-3 col-form-label fw-bold fs-6">
                                    <span class="required">{{ __('tables.Type') }}</span>
                                    <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip" title="{{ __($page->translationPrefix.'BalanceTypeInfo') }}"></i>
                                </label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-9">
                                    <select name="type_id" aria-label="{{ __($page->translationPrefix.'SelectBalanceType') }}" data-control="select2"
                                        data-placeholder="{{ __($page->translationPrefix.'SelectBalanceType') }}"
                                        data-hide-search="true"
                                        class="form-select form-select-solid form-select-lg">
                                        <option value="">{{ __($page->translationPrefix.'SelectBalanceType') }}</option>
                                        {!! $balanceTypeSelectOptions !!}
                                    </select>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-3 col-form-label required fw-bold fs-6">{{ __('tables.Target') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-9">
                                    <select name="target_id" aria-label=""{{ __($page->translationPrefix.'SelectTarget') }}" data-control="select2"
                                        data-placeholder="{{ __($page->translationPrefix.'SelectTarget') }}"
                                        class="form-select form-select-solid form-select-lg">
                                        <option value="">{{ __($page->translationPrefix.'SelectTarget') }}</option>
                                        {!! $targetSelectOptions !!}
                                    </select>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-3 col-form-label required fw-bold fs-6">{{ __('tables.Product') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-9">
                                    <input type="text" name="product_name" id="product_name" autocomplete="off"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                        placeholder="{{ __('tables.Product') }}" value="{{ $data->product_name }}" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-3 col-form-label required fw-bold fs-6">{{ __('tables.Amount') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-9">
                                    <input type="number" name="amount" id="amount"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                        placeholder="{{ __('tables.Amount') }}" value="{{ $data->amount }}" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-3 col-form-label required fw-bold fs-6">{{ __('tables.UnitPrice') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-9">
                                    <input type="number" name="unit_price" id="unit_price"
                                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                        placeholder="{{ __('tables.UnitPrice') }}" value="{{ $data->unit_price }}" />
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
<script src="{{ asset('vendors/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('vendors/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('vendors/daterangepicker/daterangepicker.js') }}"></script>
@endsection

@section('theme_js_files')
<script src="{{ asset('vendors/jquery-validate/jquery.validate.min.js') }}"></script>
<script src="{{ asset('vendors/jquery-validate/additional-methods.min.js') }}"></script>
@endsection

@section('page_js_files')
<script src="{{ asset('js/custom/strings/strings.js') }}"></script>
<script src="{{ asset('js/app/balancesheet/services/product.js') }}"></script>
<script>var userDateFormat = '{{ $userDateFormat }}';</script>
<script src="{{ asset('js/app/'.$page->routePath.'/edit.js') }}" defer="defer"></script>
@endsection

@endsection
