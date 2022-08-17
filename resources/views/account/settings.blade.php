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
        <div class="container">
            @include('account.user_preview', ['selectedMenu' => 'settings'])
            <!--begin::Basic info-->
            <div class="card mb-5 mb-xl-10">
                <!--begin::Card header-->
                <div class="card-header cursor-pointer">
                    <!--begin::Card title-->
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">{{ __('account.ProfileDetails') }}</h3>
                    </div>
                    <!--end::Card title-->
                </div>
                <!--begin::Card header-->
                <!--begin::Content-->
                <div class="collapse show">
                    <!--begin::Form-->
                    @include('errors.error')
                    @include('errors.success')
                    <form id="account_settings_form" class="form" method="post"
                        action="{{ route('account/settings/update-profile') }}" enctype="multipart/form-data">
                        @csrf
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
                                            <input type="file" name="avatar"
                                                accept="image/png, image/jpg, image/jpeg" />
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
                                    class="col-lg-4 col-form-label fw-bold fs-6">{{ __('tables.DateFormat') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-4">
                                    <select name="date_format" aria-label="{{ __('account.SelectDateFormat') }}" data-control="select2"
                                        data-placeholder="{{ __('account.SelectDateFormat') }}"
                                        data-allow-clear="true" class="form-select form-select-solid form-select-lg">
                                        <option value="">{{ __('account.SelectDateFormat') }}</option>
                                        {!! $dateFormatSelectOptions !!}
                                    </select>
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-4">
                                    <select name="date_format_separator" aria-label="{{ __('account.SelectDateFormatSeparator') }}"
                                        data-control="select2"
                                        data-placeholder="{{ __('account.SelectDateFormatSeparator') }}"
                                        data-allow-clear="true" class="form-select form-select-solid form-select-lg">
                                        <option value="">{{ __('account.SelectDateFormatSeparator') }}</option>
                                        {!! $dateFormatSeparatorSelectOptions !!}
                                    </select>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--end::Card body-->
                        <!--begin::Actions-->
                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <button type="submit" class="btn btn-primary"
                                id="account_settings_submit">{{ __('general.SaveChanges') }}</button>
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Basic info-->

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
                        <div class="d-flex flex-wrap align-items-center">
                            <!--begin::Label-->
                            <div id="signin_email">
                                <div class="fs-6 fw-bolder mb-1">{{ __('account.Email') }}</div>
                                <div class="fw-bold text-gray-600">{{ $data->email }}</div>
                            </div>
                        </div>
                        <!--end::Email Address-->
                        <!--begin::Separator-->
                        <div class="separator separator-dashed my-6"></div>
                        <!--end::Separator-->
                        <!--begin::Password-->
                        <div class="d-flex flex-wrap align-items-center mb-10">
                            <!--begin::Label-->
                            <div id="signin_password">
                                <div class="fs-6 fw-bolder mb-1">{{ __('account.Password') }}</div>
                                <div class="fw-bold text-gray-600">************</div>
                            </div>
                            <!--end::Label-->
                            <!--begin::Action-->
                            <div id="signin_password_button" class="ms-auto">
                                <button
                                    class="btn btn-light btn-active-light-primary">{{ __('account.ChangePassword') }}</button>
                            </div>
                            <!--end::Action-->
                        </div>

                        <!--begin::Edit-->
                        <div id="signin_password_edit" class="flex-row-fluid d-none">
                            <!--begin::Form-->
                            <form id="signin_change_password" class="form" method="post"
                                action="{{ route('account/settings/update-password') }}" novalidate="novalidate">
                                @csrf
                                <div class="row mb-1">
                                    <div class="col-lg-4">
                                        <div class="fv-row mb-0">
                                            <label for="current_password"
                                                class="form-label fs-6 fw-bolder mb-3">{{ __('account.CurrentPassword') }}</label>
                                            <input type="password"
                                                class="form-control form-control-lg form-control-solid"
                                                name="current_password" id="current_password" />
                                        </div>
                                    </div>
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
            <!--begin::Connected Accounts-->
            <div class="card mb-5 mb-xl-10">
                <!--begin::Card header-->
                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                    data-bs-target="#connected_accounts" aria-expanded="true" aria-controls="connected_accounts">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">{{ __('account.ConnectedAccounts') }}</h3>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Content-->
                <div id="connected_accounts" class="collapse show">
                    <!--begin::Form-->
                    <form id="update_connected_accounts" class="form" method="post"
                        action="{{ route('account/settings/update-connections') }}" novalidate="novalidate">
                        @csrf
                        <!--begin::Card body-->
                        <div class="card-body border-top p-9">
                            <!--begin::Items-->
                            <div class="py-2">
                                @if ($data->google_id)
                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <div class="d-flex">
                                        <img src="{{asset('media/theme/svg/brand-logos/google-icon.svg')}}"
                                            class="w-30px me-6" alt="" />
                                        <div class="d-flex flex-column">
                                            <a href="#"
                                                class="fs-5 text-dark text-hover-primary fw-bolder">{{ __('account.Google') }}</a>
                                            <div class="fs-6 fw-bold text-gray-400">{{ __('account.GoogleRemoveInfo') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <div class="form-check form-check-solid form-switch">
                                            <input class="form-check-input w-45px h-30px" type="checkbox"
                                                id="googleswitch" name="google_connection"
                                                {{ $data->google_id ? 'checked="checked"' : '' }} />
                                            <label class="form-check-label" for="googleswitch"></label>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Item-->
                                @endif
                                @if ($data->fb_id)
                                <div class="separator separator-dashed my-5"></div>
                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <div class="d-flex">
                                        <img src="{{asset('media/theme/svg/brand-logos/facebook-4.svg')}}"
                                            class="w-30px me-6" alt="" />
                                        <div class="d-flex flex-column">
                                            <a href="#"
                                                class="fs-5 text-dark text-hover-primary fw-bolder">{{ __('account.Facebook') }}</a>
                                            <div class="fs-6 fw-bold text-gray-400">
                                                {{ __('account.FacebookRemoveInfo') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <div class="form-check form-check-solid form-switch">
                                            <input class="form-check-input w-45px h-30px" type="checkbox" id="facebook"
                                                name="facebook_connection"
                                                {{ $data->fb_id ? 'checked="checked"' : '' }} />
                                            <label class="form-check-label" for="facebook"></label>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Item-->
                                @endif
                                @if (!$data->google_id && !$data->fb_id)
                                <!--begin::Item-->
                                <div class="d-flex flex-stack">
                                    <div class="d-flex">
                                        <div class="d-flex flex-column">
                                            <span
                                                class="fs-5 text-dark text-hover-primary fw-bolder">{{ __('account.NoAccountConnected') }}</span>
                                            <div class="fs-6 fw-bold text-gray-400">
                                                {{ __('account.ConnectAccountsInfo') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Item-->
                                @endif
                            </div>
                            <!--end::Items-->
                        </div>
                        <!--end::Card body-->
                        @if ($data->fb_id || $data->google_id)
                        <!--begin::Card footer-->
                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <button class="btn btn-primary"
                                id="connected_accounts_submit">{{ __('general.SaveChanges') }}</button>
                        </div>
                        <!--end::Card footer-->
                        @endif
                    </form>
                </div>
                <!--end::Content-->
            </div>
            <!--end::Connected Accounts-->
            {{--
            <!--begin::Notifications-->
            <div class="card mb-5 mb-xl-10">
                <!--begin::Card header-->
                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                    data-bs-target="#email_preferences" aria-expanded="true"
                    aria-controls="email_preferences">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">Email Preferences</h3>
                    </div>
                </div>
                <!--begin::Card header-->
                <!--begin::Content-->
                <div id="email_preferences" class="collapse show">
                    <!--begin::Form-->
                    <form class="form">
                        <!--begin::Card body-->
                        <div class="card-body border-top px-9 py-9">
                            <!--begin::Option-->
                            <label class="form-check form-check-custom form-check-solid align-items-start">
                                <!--begin::Input-->
                                <input class="form-check-input me-3" type="checkbox" name="email-preferences[]"
                                    value="1" />
                                <!--end::Input-->
                                <!--begin::Label-->
                                <span class="form-check-label d-flex flex-column align-items-start">
                                    <span class="fw-bolder fs-5 mb-0">Successful Payments</span>
                                    <span class="text-muted fs-6">Receive a notification for every successful
                                        payment.</span>
                                </span>
                                <!--end::Label-->
                            </label>
                            <!--end::Option-->
                            <!--begin::Option-->
                            <div class="separator separator-dashed my-6"></div>
                            <!--end::Option-->
                            <!--begin::Option-->
                            <label class="form-check form-check-custom form-check-solid align-items-start">
                                <!--begin::Input-->
                                <input class="form-check-input me-3" type="checkbox" name="email-preferences[]"
                                    checked="checked" value="1" />
                                <!--end::Input-->
                                <!--begin::Label-->
                                <span class="form-check-label d-flex flex-column align-items-start">
                                    <span class="fw-bolder fs-5 mb-0">Payouts</span>
                                    <span class="text-muted fs-6">Receive a notification for every initiated
                                        payout.</span>
                                </span>
                                <!--end::Label-->
                            </label>
                            <!--end::Option-->
                            <!--begin::Option-->
                            <div class="separator separator-dashed my-6"></div>
                            <!--end::Option-->
                            <!--begin::Option-->
                            <label class="form-check form-check-custom form-check-solid align-items-start">
                                <!--begin::Input-->
                                <input class="form-check-input me-3" type="checkbox" name="email-preferences[]"
                                    value="1" />
                                <!--end::Input-->
                                <!--begin::Label-->
                                <span class="form-check-label d-flex flex-column align-items-start">
                                    <span class="fw-bolder fs-5 mb-0">Fee Collection</span>
                                    <span class="text-muted fs-6">Receive a notification each time you collect a fee
                                        from sales</span>
                                </span>
                                <!--end::Label-->
                            </label>
                            <!--end::Option-->
                        </div>
                        <!--end::Card body-->
                        <!--begin::Card footer-->
                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <button class="btn btn-light btn-active-light-primary me-2">Discard</button>
                            <button class="btn btn-primary px-6">Save Changes</button>
                        </div>
                        <!--end::Card footer-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Notifications-->
            --}}
            <!--begin::Delete Account-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
                    data-bs-target="#account_delete" aria-expanded="true" aria-controls="account_delete">
                    <div class="card-title m-0">
                        <h3 class="fw-bolder m-0">{{ __('account.DeleteAccount') }}</h3>
                    </div>
                </div>
                <!--end::Card header-->
                <!--begin::Content-->
                <div id="account_delete" class="collapse show">
                    <!--begin::Form-->
                    <form id="account_delete_form" class="form" method="post"
                        action="{{ route('account/settings/delete-account') }}" novalidate="novalidate">
                        @csrf
                        <!--begin::Card body-->
                        <div class="card-body border-top p-9">
                            <!--begin::Notice-->
                            <div
                                class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
                                <!--begin::Icon-->
                                <!--begin::Svg Icon | path: icons/duotone/Code/Warning-1-circle.svg-->
                                <span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px"
                                        viewBox="0 0 24 24" version="1.1">
                                        <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10" />
                                        <rect fill="#000000" x="11" y="7" width="2" height="8" rx="1" />
                                        <rect fill="#000000" x="11" y="16" width="2" height="2" rx="1" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                                <!--end::Icon-->
                                <!--begin::Wrapper-->
                                <div class="d-flex flex-stack flex-grow-1">
                                    <!--begin::Content-->
                                    <div class="fw-bold">
                                        <h4 class="text-gray-900 fw-bolder">{{ __('account.DeleteAccountInfoTitle') }}
                                        </h4>
                                        <div class="fs-6 text-gray-700">
                                            {{ __('account.DeleteAccountInfoDescription') }}
                                            <br />
                                        </div>
                                    </div>
                                    <!--end::Content-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                            <!--end::Notice-->
                            <!--begin::Form input row-->
                            <div class="form-check form-check-solid fv-row">
                                <input name="confirm_delete" class="form-check-input" type="checkbox" value="1"
                                    id="confirm_delete" />
                                <label class="form-check-label fw-bold ps-2 fs-6"
                                    for="confirm_delete">{{ __('account.ConfirmAccountDelete') }}</label>
                            </div>
                            <!--end::Form input row-->
                        </div>
                        <!--end::Card body-->
                        <!--begin::Card footer-->
                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <button id="account_delete_submit" type="submit"
                                class="btn btn-danger fw-bold">{{ __('account.DeleteAccount') }}</button>
                        </div>
                        <!--end::Card footer-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Deactivate Account-->
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
<script src="{{ asset('js/app/account/settings.js') }}" defer="defer"></script>
@endsection

@endsection
