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
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __($page->translationPrefix.'Avatar') }}</label>
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
                                            title="{{ __($page->translationPrefix.'ChangeAvatar') }}">
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
                                            title="{{ __($page->translationPrefix.'CancelAvatar') }}">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                        <!--end::Cancel-->
                                        <!--begin::Remove-->
                                        @if ($data->hasAvatar)
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                            title="{{ __($page->translationPrefix.'RemoveAvatar') }}">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                        @endif
                                        <!--end::Remove-->
                                    </div>
                                    <!--end::Image input-->
                                    <!--begin::Hint-->
                                    <div class="form-text">{{ __($page->translationPrefix.'AllowedFileTypes') }}</div>
                                    <!--end::Hint-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{ __($page->translationPrefix.'FullName') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8">
                                    <!--begin::Row-->
                                    <div class="row">
                                        <!--begin::Col-->
                                        <div class="col-lg-6 fv-row">
                                            <input type="text" name="first_name"
                                                class="form-control form-control-lg form-control-solid mb-3 mb-lg-0"
                                                placeholder="{{ __($page->translationPrefix.'FirstName') }}"
                                                value="{{ $data->first_name }}" />
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-lg-6 fv-row">
                                            <input type="text" name="last_name"
                                                class="form-control form-control-lg form-control-solid"
                                                placeholder="{{ __($page->translationPrefix.'LastName') }}"
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
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{ __($page->translationPrefix.'Email') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="email" name="email"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="{{ __($page->translationPrefix.'Email') }}" value="{{ $data->email }}" />
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label
                                    class="col-lg-4 col-form-label required fw-bold fs-6">{{ __($page->translationPrefix.'Password') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <input type="password" name="password"
                                        class="form-control form-control-lg form-control-solid"
                                        placeholder="{{ __($page->translationPrefix.'Password') }}" value="{{ $data->password }}" />
                                </div>
                                <!--end::Col-->
                            </div>
                            @endif
                            <!--begin::Input group-->
                            <div class="row mb-6">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __($page->translationPrefix.'Currency') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-8 fv-row">
                                    <select name="currency" aria-label="{{ __($page->translationPrefix.'SelectCurrency') }}" data-control="select2"
                                        data-placeholder="{{ __($page->translationPrefix.'SelectCurrency') }}" data-kt-flags="true"
                                        class="form-select form-select-solid form-select-lg">
                                        <option value="">{{ __($page->translationPrefix.'SelectCurrency') }}</option>
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
                                    <select name="date_format" aria-label="{{ __($page->translationPrefix.'SelectDateFormat') }}" data-control="select2"
                                        data-placeholder="{{ __($page->translationPrefix.'SelectDateFormat') }}"
                                        data-allow-clear="true" class="form-select form-select-solid form-select-lg">
                                        <option value="">{{ __($page->translationPrefix.'SelectDateFormat') }}</option>
                                        {!! $dateFormatSelectOptions !!}
                                    </select>
                                </div>
                                <!--end::Col-->
                                <!--begin::Col-->
                                <div class="col-lg-4">
                                    <select name="date_format_separator" aria-label="{{ __($page->translationPrefix.'SelectDateFormatSeparator') }}"
                                        data-control="select2"
                                        data-placeholder="{{ __($page->translationPrefix.'SelectDateFormatSeparator') }}"
                                        data-allow-clear="true" class="form-select form-select-solid form-select-lg">
                                        <option value="">{{ __($page->translationPrefix.'SelectDateFormatSeparator') }}</option>
                                        {!! $dateFormatSeparatorSelectOptions !!}
                                    </select>
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                            <!--begin::Input group-->
                            <div class="row mb-0">
                                <!--begin::Label-->
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __($page->translationPrefix.'Active') }}</label>
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
                                <label class="col-lg-4 col-form-label fw-bold fs-6">{{ __($page->translationPrefix.'Google') }}
                                    <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip"
                                        title="{{ __($page->translationPrefix.'GoogleInfo') }}"></i>
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
                        <h3 class="fw-bolder m-0">{{ __($page->translationPrefix.'SignInMethod') }}</h3>
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
                                <div class="fs-6 fw-bolder mb-1">{{ __($page->translationPrefix.'Email') }}</div>
                                <div class="fw-bold text-gray-600">{{ $data->email }}</div>
                            </div>
                            <!--begin::Action-->
                            <div id="email_button" class="ms-auto">
                                <button
                                    class="btn btn-light btn-active-light-primary">{{ __($page->translationPrefix.'ChangeEmail') }}</button>
                            </div>
                            <!--end::Action-->
                        </div>
                        <!--begin::Edit-->
                        <div id="email_edit" class="flex-row-fluid d-none">
                            <!--begin::Form-->
                            <form id="change_email" class="form" method="post"
                                action="{{ route($page->routePath.'/update-email', ['id' => $data->id]) }}" novalidate="novalidate">
                                @csrf
                                <div class="row mb-5">
                                    <div class="col-lg-4">
                                        <div class="fv-row mb-0">
                                            <label for="new_email"
                                                class="form-label fs-6 fw-bolder mb-3">{{ __($page->translationPrefix.'NewEmail') }}</label>
                                            <input type="email" class="form-control form-control-lg form-control-solid"
                                                name="new_email" id="new_email" />
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <button id="email_submit" type="button"
                                        class="btn btn-primary me-2 px-6">{{ __($page->translationPrefix.'UpdateEmail') }}</button>
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
                                <div class="fs-6 fw-bolder mb-1">{{ __($page->translationPrefix.'Password') }}</div>
                                <div class="fw-bold text-gray-600">************</div>
                            </div>
                            <!--end::Label-->
                            <!--begin::Action-->
                            <div id="password_button" class="ms-auto">
                                <button
                                    class="btn btn-light btn-active-light-primary">{{ __($page->translationPrefix.'ChangePassword') }}</button>
                            </div>
                            <!--end::Action-->
                        </div>

                        <!--begin::Edit-->
                        <div id="password_edit" class="flex-row-fluid d-none">
                            <!--begin::Form-->
                            <form id="change_password" class="form" method="post"
                                action="{{ route($page->routePath.'/update-password', ['id' => $data->id]) }}"
                                novalidate="novalidate">
                                @csrf
                                <div class="row mb-1">
                                    <div class="col-lg-4">
                                        <div class="fv-row mb-0">
                                            <label for="new_password"
                                                class="form-label fs-6 fw-bolder mb-3">{{ __($page->translationPrefix.'NewPassword') }}</label>
                                            <input type="password"
                                                class="form-control form-control-lg form-control-solid"
                                                name="new_password" id="new_password" />
                                        </div>
                                        <div class="form-text mb-5">{{ __($page->translationPrefix.'PasswordInfo') }}</div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="fv-row mb-0">
                                            <label for="password_confirmation"
                                                class="form-label fs-6 fw-bolder mb-3">{{ __($page->translationPrefix.'ConfirmNewPassword') }}</label>
                                            <input type="password"
                                                class="form-control form-control-lg form-control-solid"
                                                name="password_confirmation" id="password_confirmation" />
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <button id="password_submit" type="button"
                                        class="btn btn-primary me-2 px-6">{{ __($page->translationPrefix.'UpdatePassword') }}</button>
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
<script src="{{ asset('vendors/jquery-validate/jquery.validate.min.js') }}"></script>
<script src="{{ asset('vendors/jquery-validate/additional-methods.min.js') }}"></script>
@endsection

@section('page_js_files')
<script src="{{ asset('js/app/users/edit.js') }}" defer="defer"></script>
@endsection

@endsection
