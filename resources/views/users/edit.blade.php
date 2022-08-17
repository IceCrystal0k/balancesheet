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
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('account.Avatar') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <!--begin::Image input-->
                                    <div class="image-input image-input-outline" data-kt-image-input="true"
                                        style="background-image: url({{ asset('media/theme/avatars/blank.png') }})">
                                        <!--begin::Preview existing avatar-->
                                        <div class="image-input-wrapper w-125px h-125px"
                                            style="background-image: url({{ $data->avatar_edit }})"></div>
                                        <!--end::Preview existing avatar-->
                                        <!--begin::Label-->
                                        <label
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                            title="{{ __('account.ChangeAvatar') }}">
                                            <i class="bi bi-pencil-fill fs-7"></i>
                                            <!--begin::Inputs-->
                                            <input type="file" name="avatar" accept="image/png,image/jpeg" />
                                            <input type="hidden" name="avatar_remove" id="avatar_remove" />
                                            <!--end::Inputs-->
                                        </label>
                                        <!--end::Label-->
                                        <!--begin::Cancel-->
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                            title="{{ __('account.CancelAvatar') }}">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                        <!--end::Cancel-->
                                        <!--begin::Remove-->
                                        @if ($data->hasAvatar)
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                            title="{{ __('account.RemoveAvatar') }}">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                        @endif
                                        <!--end::Remove-->
                                    </div>
                                    <!--end::Image input-->
                                    <!--begin::Hint-->
                                    <div class="form-text">{{ __('account.AllowedFileTypes') }}</div>
                                    <!--end::Hint-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('account.FullName') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <!--begin::Row-->
                                    <div class="row">
                                        <!--begin::Col-->
                                        <div class="col-lg-6 fv-row">
                                            <input type="text" name="first_name"
                                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                placeholder="{{ __('account.FirstName') }}"
                                                value="{{ $data->first_name }}" />
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6 fv-row">
                                            <input type="text" name="last_name"
                                                class="form-control form-control-lg form-control-solid"
                                                placeholder="{{ __('account.LastName') }}"
                                                value="{{ $data->last_name }}" />
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Row-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            @if (!$data->id)
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('account.Email') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="email" name="email"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="{{ __('account.Email') }}" value="{{ $data->email }}" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('account.Password') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="password" name="password"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="{{ __('account.Password') }}" value="{{ $data->password }}" />
                                </div>
                                <!--end::Col-->
                            </div>
                            @endif
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('account.Company') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="company"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="{{ __('account.Company') }}" value="{{ $data->company }}" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6">{{ __('account.ContactPhone') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="tel" name="phone"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="{{ __('account.PhoneNumber') }}" value="{{ $data->phone }}" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6">{{ __('account.CompanySite') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="text" name="website"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="{{ __('account.CompanySite') }}" value="{{ $data->website }}" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">
                                    <span class="required">{{ __('account.Country') }}</span>
                                    <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip"
                                        title="{{ __('account.CountryOfOrigination') }}"></i>
                                </label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <select name="country" aria-label="{{ __('account.SelectCountry') }}" data-control="select2"
                                        data-placeholder="{{ __('account.SelectCountry') }}" data-kt-flags="true"
                                        class="form-select form-select-solid form-select-lg fw-bold">
                                        <option value="">{{ __('account.SelectCountry') }}</option>
                                        {!! $countrySelectOptions !!}
                                    </select>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{ __('account.Language') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <!--begin::Input-->
                                    <select name="language" aria-label="{{ __('account.SelectLanguage') }}" data-control="select2"
                                        data-placeholder="{{ __('account.SelectLanguage') }}" data-kt-flags="true"
                                        class="form-select form-select-solid form-select-lg">
                                        <option value="">{{ __('account.SelectLanguage') }}</option>
                                        {!! $languageSelectOptions !!}
                                    </select>
                                    <!--end::Input-->
                                    <!--begin::Hint-->
                                    <div class="form-text">{{ __('account.SelectPreferredLanguage') }}</div>
                                    <!--end::Hint-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('account.TimeZone') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <select name="timezone" aria-label="{{ __('account.SelectTimezone') }}" data-control="select2"
                                        data-placeholder="{{ __('account.SelectTimezone') }}"
                                        class="form-select form-select-solid form-select-lg">
                                        <option value="">{{ __('account.SelectTimezone') }}</option>
                                        {!! $timezoneSelectOptions !!}
                                    </select>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('account.Currency') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <select name="currency" aria-label="{{ __('account.SelectCurrency') }}" data-control="select2"
                                        data-placeholder="{{ __('account.SelectCurrency') }}" data-kt-flags="true"
                                        class="form-select form-select-solid form-select-lg">
                                        <option value="">{{ __('account.SelectCurrency') }}</option>
                                        {!! $currencySelectOptions !!}
                                    </select>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6">{{ __('account.Communication') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <!--begin::Options-->
                                    <div class="d-flex align-items-center mt-3">
                                        <!--begin::Option-->
                                        <label class="form-check form-check-inline form-check-solid me-5">
                                            <input class="form-check-input" name="communication[]" type="checkbox"
                                                value="email"
                                                {{ isset($data->communication->email) ? 'checked="checked"' : '' }} />
                                            <span class="fw-bold ps-2 fs-6">{{ __('account.Email') }}</span>
                                        </label>
                                        <!--end::Option-->
                                        <!--begin::Option-->
                                        <label class="form-check form-check-inline form-check-solid">
                                            <input class="form-check-input" name="communication[]" type="checkbox"
                                                value="phone"
                                                {{ isset($data->communication->phone) ? 'checked="checked"' : '' }} />
                                            <span class="fw-bold ps-2 fs-6">{{ __('account.Phone') }}</span>
                                        </label>
                                        <!--end::Option-->
                                    </div>
                                    <!--end::Options-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-0">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-4 col-form-label fw-bold fs-6">{{ __('account.AllowMarketing') }}</label>
                                <!--begin::Label-->
                                <!--begin::Label-->
                                <div class="col-lg-8 d-flex align-items-center">
                                    <div class="form-check form-check-solid form-switch fv-row">
                                        <input class="form-check-input w-45px h-30px" type="checkbox" name="marketing"
                                            value="1" id="marketing"
                                            {{ $data->marketing ? 'checked="checked"' : '' }} />
                                        <label class="form-check-label" for="marketing"></label>
                                    </div>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-0">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('account.Active') }}</label>
                                <!--begin::Label-->
                                <!--begin::Label-->
                                <div class="col-lg-8 d-flex align-items-center">
                                    <div class="form-check form-check-solid form-switch fv-row">
                                        <input class="form-check-input w-45px h-30px" type="checkbox" name="status"
                                            value="1" id="status" {{ $data->status ? 'checked="checked"' : '' }} />
                                        <label class="form-check-label" for="status"></label>
                                    </div>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Input group-->
                            @if ($data->google_id)
                            <!--begin::Input group-->
                            <div class="row mb-0">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __('account.Google') }}
                                    <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip"
                                        title="{{ __('account.GoogleInfo') }}"></i>
                                </label>
                                <!--begin::Label-->
                                <!--begin::Label-->
                                <div class="col-lg-8 d-flex align-items-center">
                                    <div class="form-check form-check-solid form-switch fv-row">
                                        <input class="form-check-input w-45px h-30px" type="checkbox" name="google_id"
                                            value="1" id="google_id" disabled="disabled"
                                            {{ $data->google_id ? 'checked="checked"' : '' }} />
                                        <label class="form-check-label" for="google_id"></label>
                                    </div>
                                </div>
                                <!--end::Label-->
                            </div>
                            <!--end::Input group-->
                            @endif
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

            @if ($data->id)
            <!--begin::Sign-in Method-->
            <div class="card mb-5 mb-xl-10">
                <!--begin::Card header-->
                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                    data-bs-target="#signin_method">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">{{ __('account.SignInMethod') }}</h3>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Content-->
                <div id="signin_method" class="collapse show">
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
                        <!--begin::Email Address-->
                        <div class="d-flex flex-wrap align-items-center mb-10">
                            <!--begin::Label-->
                            <div id="email">
                                <div class="fs-6 fw-bolder mb-1">{{ __('account.Email') }}</div>
                                <div class="fw-bold text-gray-600">{{ $data->email }}</div>
                            </div>
                            <!--begin::Action-->
                            <div id="email_button" class="ms-auto">
                                <button
                                    class="btn btn-light btn-active-light-primary">{{ __('account.ChangeEmail') }}</button>
                            </div>
                            <!--end::Action-->
                        </div>
                        <!--begin::Edit-->
                        <div id="email_edit" class="flex-row-fluid d-none">
                            <!--begin::Form-->
                            <form id="change_email" class="form" method="post"
                                action="{{ route('users/update-email', ['id' => $data->id]) }}" novalidate="novalidate">
                                @csrf
                                <div class="row mb-5">
                                    <div class="col-lg-4">
                                        <div class="fv-row mb-0">
                                            <label for="new_email"
                                                class="form-label fs-6 fw-bolder mb-3">{{ __('account.NewEmail') }}</label>
                                            <input type="email" class="form-control form-control-lg form-control-solid"
                                                name="new_email" id="new_email" />
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <button id="email_submit" type="button"
                                        class="btn btn-primary me-2 px-6">{{ __('account.UpdateEmail') }}</button>
                                    <button id="email_cancel" type="button"
                                        class="btn btn-color-gray-400 btn-active-light-primary px-6">{{ __('general.Cancel') }}</button>
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Edit-->
                        <!--end::Email Address-->
                        <!--begin::Separator-->
                        <div class="separator separator-dashed my-6"></div>
                        <!--end::Separator-->
                        <!--begin::Password-->
                        <div class="d-flex flex-wrap align-items-center mb-10">
                            <!--begin::Label-->
                            <div id="password">
                                <div class="fs-6 fw-bolder mb-1">{{ __('account.Password') }}</div>
                                <div class="fw-bold text-gray-600">************</div>
                            </div>
                            <!--end::Label-->
                            <!--begin::Action-->
                            <div id="password_button" class="ms-auto">
                                <button
                                    class="btn btn-light btn-active-light-primary">{{ __('account.ChangePassword') }}</button>
                            </div>
                            <!--end::Action-->
                        </div>

                        <!--begin::Edit-->
                        <div id="password_edit" class="flex-row-fluid d-none">
                            <!--begin::Form-->
                            <form id="change_password" class="form" method="post"
                                action="{{ route('users/update-password', ['id' => $data->id]) }}"
                                novalidate="novalidate">
                                @csrf
                                <div class="row mb-1">
                                    <div class="col-lg-4">
                                        <div class="fv-row mb-0">
                                            <label for="new_password"
                                                class="form-label fs-6 fw-bolder mb-3">{{ __('account.NewPassword') }}</label>
                                            <input type="password"
                                                class="form-control form-control-lg form-control-solid"
                                                name="new_password" id="new_password" />
                                        </div>
                                        <div class="form-text mb-5">{{ __('account.PasswordInfo') }}</div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="fv-row mb-0">
                                            <label for="password_confirmation"
                                                class="form-label fs-6 fw-bolder mb-3">{{ __('account.ConfirmNewPassword') }}</label>
                                            <input type="password"
                                                class="form-control form-control-lg form-control-solid"
                                                name="password_confirmation" id="password_confirmation" />
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <button id="password_submit" type="button"
                                        class="btn btn-primary me-2 px-6">{{ __('account.UpdatePassword') }}</button>
                                    <button id="password_cancel" type="button"
                                        class="btn btn-color-gray-400 btn-active-light-primary px-6">{{ __('general.Cancel') }}</button>
                                </div>
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Edit-->
                        <!--end::Password-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Sign-in Method-->
            @endif
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>

<!--end::Content-->

@section('vendor_js_files')
<script src="{{ asset('vendors/i18next/i18next.min.js') }}"></script>
<script src="{{ asset('vendors/sweetalert2/sweetalert2.min.js') }}"></script>
@endsection

@section('theme_js_files')
<script src="{{ asset('vendors/jquery-validate/jquery.validate.min.js') }}"></script>
<script src="{{ asset('vendors/jquery-validate/additional-methods.min.js') }}"></script>
@endsection

@section('page_js_files')
<script src="{{ asset('js/app/users/edit.js') }}" defer="defer"></script>
@endsection

@endsection
