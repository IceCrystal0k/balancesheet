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
        @include('errors.status')

        <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" method="post"
            action="{{ route('login') }}">
            @csrf
            <!--begin::Heading-->
            <div class="text-center mb-10">
                <!--begin::Title-->
                <h1 class="text-dark mb-3">{{__('login.SignInTitle')}}</h1>
                <!--end::Title-->
                <!--begin::Link-->
                <div class="text-gray-400 fw-bold fs-4">{{__('login.NewHere')}}
                    <a href="{{ route('register') }}" class="link-primary fw-bolder">{{__('login.CreateAnAccount')}}</a>
                </div>
                <!--end::Link-->
            </div>
            <!--begin::Heading-->
            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <label class="form-label fs-6 fw-bolder text-dark">{{__('login.Email')}}</label>
                <input class="form-control form-control-lg form-control-solid" type="text" id="email" name="email"
                    value="{{ old('email') }}" autocomplete="off" />
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <div class="d-flex flex-stack mb-2">
                    <label class="form-label fw-bolder text-dark fs-6 mb-0">{{__('login.Password')}}</label>
                    <a href="{{ route('password.request') }}"
                        class="link-primary fs-6 fw-bolder">{{__('login.ForgotPassword')}}</a>
                </div>
                <input class="form-control form-control-lg form-control-solid" type="password" name="password"
                    autocomplete="off" />
            </div>
            <!--end::Input group-->
            <!--begin::Actions-->
            <div class="text-center">
                <!--begin::Submit button-->
                <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                    <span class="indicator-label">{{__('login.Continue')}}</span>
                    <span class="indicator-progress">{{__('login.PleaseWait')}}
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <!--end::Submit button-->
                <!--begin::Separator-->
                <div class="text-center text-muted text-uppercase fw-bolder mb-5">{{__('login.or')}}</div>
                <!--end::Separator-->
                <!--begin::Google link-->
                <a href="{{ url('auth/google') }}" class="btn btn-flex flex-center btn-light btn-lg w-100 mb-5">
                    <img alt="Logo" src="{{ asset('media/theme/svg/brand-logos/google-icon.svg') }}"
                        class="h-20px me-3" />{{__('login.ContinueWithGoogle')}}</a>
                <!--end::Google link-->
                <!--begin::Google link-->
                <a href="{{ url('auth/facebook') }}" class="btn btn-flex flex-center btn-light btn-lg w-100 mb-5">
                    <img alt="Logo" src="{{ asset('media/theme/svg/brand-logos/facebook-4.svg') }}"
                        class="h-20px me-3" />{{__('login.ContinueWithFacebook')}}</a>
                <!--end::Google link-->
                <!--begin::Google link-->
                <!-- <a href="#" class="btn btn-flex flex-center btn-light btn-lg w-100">
                    <img alt="Logo" src="{{ asset('media/theme/svg/brand-logos/apple-black.svg') }}"
                        class="h-20px me-3" />{{__('login.ContinueWithApple')}}</a> -->
                <!--end::Google link-->
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
<script src="{{ asset('js/app/authentication/login.js') }}" defer="defer"></script>
@endsection
