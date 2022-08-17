@extends('layouts.main')
@section('content')
<!--begin::Content-->
<div class="d-flex flex-column flex-column-fluid text-center p-10 py-lg-20">
    <!--begin::Wrapper-->
    <div class="pt-lg-10">
        <!--begin::Logo-->
        <h1 class="fw-bolder fs-4x text-gray-800 mb-10">{{ __('general.PageNotFound') }}</h1>
        <!--end::Logo-->
        <!--begin::Message-->
        <div class="fw-bold fs-3 text-muted mb-15">{{ $data['info'] }}
            <br />{{ $data['description'] }}
        </div>
        <!--end::Message-->
        <!--begin::Action-->
        <div class="text-center">
            <a href="{{ route('dashboard') }}"
                class="btn btn-lg btn-primary fw-bolder">{{ __('general.GoToHomepage') }}</a>
        </div>
        <!--end::Action-->
    </div>
    <!--end::Wrapper-->
</div>
<!--end::Content-->
@endsection