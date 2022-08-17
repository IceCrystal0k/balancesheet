@extends('layouts.auth')

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

        <!--begin::Heading-->
        <!--begin::Title-->
        <h1 class="fw-bolder fs-2qx text-white-800 mb-7 {{ $data->css}}">{{ $data->title }}</h1>
        <!--end::Title-->
        <!--begin::Link-->
        <div class="fs-3 fw-bold text-white-300 mb-10">{{ $data->info }}</div>
        <!--end::Link-->

        <!--end::Action-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Wrapper-->
</div>
<!--end::Content-->
@endsection