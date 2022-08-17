@extends('layouts.auth')

@section('styles')
<link href="{{ asset('vendors/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<!-- Content area -->
<!--begin::Content-->
<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
    <!--begin::Logo-->
    <a href="{{route('login')}}" class="mb-12">
        <img alt="Logo" src="{{ asset('media/logos/logo-login.png') }}" class="h-45px" />
    </a>
    <!--end::Logo-->
    <!--begin::Wrapper-->
    <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
        <!--begin::Form-->
        @include('errors.error')
        @include('errors.success')

        <form class="form w-100" novalidate="novalidate" id="kt_new_password_form" method="post"
            action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ request()->route('token') }}" />
            <input type="hidden" name="email" value="{{ request()->email }}" />
            <!--begin::Heading-->
            <div class="text-center mb-10">
                <!--begin::Title-->
                <h1 class="text-dark mb-3">{{__('login.SetupNewPassword')}}</h1>
                <!--end::Title-->
                <!--begin::Link-->
                <div class="text-gray-400 fw-bold fs-4">{{__('login.AlreadyResetYourPassword')}}
                    <a href="{{ route('login') }}" class="link-primary fw-bolder">{{__('login.SignInHere')}}</a>
                </div>
                <!--end::Link-->
            </div>
            <!--begin::Heading-->
            <!--begin::Input group-->
            <div class="mb-10 fv-row" data-kt-password-meter="true">
                <!--begin::Wrapper-->
                <div class="mb-1">
                    <!--begin::Label-->
                    <label class="form-label fw-bolder text-dark fs-6">{{__('login.Password')}}</label>
                    <!--end::Label-->
                    <!--begin::Input wrapper-->
                    <div class="position-relative mb-3">
                        <input class="form-control form-control-lg form-control-solid" type="password" placeholder=""
                            name="password" id="password" autocomplete="off" />
                        <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                            data-kt-password-meter-control="visibility">
                            <i class="bi bi-eye-slash fs-2"></i>
                            <i class="bi bi-eye fs-2 d-none"></i>
                        </span>
                    </div>
                    <!--end::Input wrapper-->
                    <!--begin::Meter-->
                    <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                        <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                    </div>
                    <!--end::Meter-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Hint-->
                <div class="text-muted">{{__('login.PasswordInfo')}}</div>
                <!--end::Hint-->
            </div>
            <!--end::Input group=-->
            <!--begin::Input group=-->
            <div class="fv-row mb-10">
                <label class="form-label fw-bolder text-dark fs-6">{{__('login.ConfirmPassword')}}</label>
                <input class="form-control form-control-lg form-control-solid" type="password" placeholder=""
                    name="password_confirmation" autocomplete="off" />
            </div>
            <!--end::Input group=-->
            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <label class="form-check form-check-custom form-check-solid form-check-inline">
                    <input class="form-check-input" type="checkbox" name="agree_tc" value="1" />
                    <span class="form-check-label fw-bold text-gray-700 fs-6">{{__('login.IAgree')}}
                        <a href="#" class="ms-1 link-primary">{{__('login.TermsAndConditions')}}</a></span>
                </label>
            </div>
            <!--end::Input group-->
            <!--begin::Actions-->
            <div class="text-center">
                <!--begin::Submit button-->
                <button type="submit" id="kt_new_password_submit" class="btn btn-lg btn-primary w-100 mb-5">
                    <span class="indicator-label">{{__('login.Continue')}}</span>
                    <span class="indicator-progress">{{__('login.PleaseWait')}}
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <!--end::Submit button-->
            </div>
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Wrapper-->
</div>
<!--end::Content-->
@endsection

@section('vendor_js_files')
<script src="{{ asset('vendors/i18next/i18next.min.js') }}"></script>
<script src="{{ asset('vendors/sweetalert2/sweetalert2.min.js') }}"></script>
@endsection

@section('theme_js_files')
<script src="{{ asset('vendors/jquery-validate/jquery.validate.min.js') }}"></script>
<script src="{{ asset('vendors/jquery-validate/additional-methods.min.js') }}"></script>
@endsection

@section('page_js_files')
<script src="{{ asset('js/app/authentication/reset-password.js') }}" defer="defer"></script>
@endsection