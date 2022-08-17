@extends('layouts.auth')

@section('styles')
<link href="{{ asset('vendors/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<!-- Content area -->
<!--begin::Content-->
<div class="d-flex flex-column flex-column-fluid text-center p-10 py-lg-20">
    <!--begin::Logo-->
    <a href="{{route('login')}}" class="mb-12">
        <img alt="Logo" src="{{ asset('media/logos/logo-login.png') }}" class="h-45px" />
    </a>
    <!--end::Logo-->
    <!--begin::Wrapper-->
    <div class="pt-lg-10">
        <!--begin::Form-->
        <div class=" w-lg-500px mx-auto">
            @include('errors.error')
            @include('errors.success')
        </div>

        <form class="form w-100" novalidate="novalidate" id="kt_verify_email_form" method="post"
            action="{{ route('verification.send') }}">
            @csrf
            <!--begin::Heading-->
            <!--begin::Title-->
            <h1 class="fw-bolder fs-2qx text-white-800 mb-7">{{ __('login.VerifyYourEmail') }}</h1>
            <!--end::Title-->
            <!--begin::Link-->
            <div class="fs-3 fw-bold text-white-300 mb-10">{!! sprintf(__('login.EmailSentInfo'), $email) !!}
            </div>
            <!--end::Link-->


            <!--begin::Action-->
            <div class="fs-5">
                <span class="fw-bold text-white-300">{{ __('login.DidntReceiveEmail') }}</span>
                <button type="submit" id="kt_verify_email_submit" class="btn btn-lg btn-primary ms-5">
                    <span class="indicator-label">{{__('login.Resend')}}</span>
                    <span class="indicator-progress">{{__('login.PleaseWait')}}
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </div>
            <!--end::Action-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Wrapper-->
</div>
<!--end::Content-->
@endsection


@section('page_js_files')
<script src="{{ asset('js/app/authentication/verify-email.js') }}" defer="defer"></script>
@endsection