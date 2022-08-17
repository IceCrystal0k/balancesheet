@extends('layouts.main')
@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid">
    @include('partials.page.toolbar')
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            @include('account.user_preview', ['selectedMenu' => 'profile'])
            <!--begin::details View-->
            <div class="card mb-5 mb-xl-10">
                <!--begin::Card header-->
                <div class="card-header cursor-pointer">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">{{ __('account.ProfileDetails') }}</h3>
                    </div>
                    <!--end::Card title-->
                    <!--begin::Action-->
                    <!--begin::Actions-->
                    <a href="{{ route('account/settings') }}"
                        class="btn btn-primary align-self-center">{{ __('account.EditProfile') }}</a>
                    <!--end::Action-->
                </div>
                <!--begin::Card header-->
                <!--begin::Card body-->
                <div class="card-body p-9">
                    <!--begin::Row-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted">{{ __('account.FullName') }}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <span class="fw-bolder fs-6 text-gray-800">{{ $data->name}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Row-->
                    @if ($data->company)
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted">{{ __('account.Company') }}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 fv-row">
                            <span class="fw-bold text-gray-800 fs-6">{{ $data->company}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    @endif
                    @if ($data->phone)
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted">{{ __('account.ContactPhone') }}
                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                title="Phone number must be active"></i></label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 d-flex align-items-center">
                            <span class="fw-bolder fs-6 text-gray-800 me-2">{{ $data->phone}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    @endif
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted">{{ __('account.Email') }}
                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                title="Phone number must be active"></i></label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8 d-flex align-items-center">
                            <span class="fw-bolder fs-6 text-gray-800 me-2">{{ $data->email}}</span>
                            <span class="badge badge-success">Verified</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    @if ($data->website)
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted">{{ __('account.CompanySite') }}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <a href="#" class="fw-bold fs-6 text-gray-800 text-hover-primary">{{ $data->website}}</a>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    @endif
                    @if ($data->country)
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted">{{ __('account.Country') }}
                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                title="Country of origination"></i></label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <span class="fw-bolder fs-6 text-gray-800"><img width="22"
                                    src="{{ $data->country->flag }}" />
                                {{ $data->country->name}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    @endif
                    @if ($data->language)
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted">{{ __('account.Language') }}
                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                title="Preferred language"></i></label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <span class="fw-bolder fs-6 text-gray-800"><img width="22"
                                    src="{{ $data->language->flag }}" />
                                {{ $data->language->name}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    @endif
                    @if ($data->timezone)
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted">{{ __('account.TimeZone') }}
                            <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                title="Timezone"></i></label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <span class="fw-bolder fs-6 text-gray-800">{{ $data->timezone}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    @endif
                    @if ($data->communication_display)
                    <!--begin::Input group-->
                    <div class="row mb-7">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted">{{ __('account.Communication') }}</label>
                        <!--end::Label-->
                        <!--begin::Col-->
                        <div class="col-lg-8">
                            <span class="fw-bolder fs-6 text-gray-800">{{ $data->communication_display}}</span>
                        </div>
                        <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    @endif
                    <!--begin::Input group-->
                    <div class="row mb-10">
                        <!--begin::Label-->
                        <label class="col-lg-4 fw-bold text-muted">{{ __('account.Status') }}</label>
                        <!--begin::Label-->
                        <!--begin::Label-->
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $data->status_name}}</span>
                        </div>
                        <!--begin::Label-->
                    </div>
                    <!--end::Input group-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::details View-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->
@endsection
