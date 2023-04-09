@extends('partials.page.filter', ['globalSearchPlaceholder' => __($page->translationPrefix.'SearchTable'), 'createNewLabel' =>
__('user.CreateNew')])
@section('filter_content')
<!--begin::Input group-->
<div class="mb-5">
    <!--begin::Label-->
    <label class="form-label fs-5 fw-bold mb-3">{{ __('tables.Status') }}:</label>
    <!--end::Label-->
    <!--begin::Options-->
    <div class="d-flex flex-wrap fw-bold" id="filterStatus">
        @foreach ($statusFilter as $option)
        <!--begin::Option-->
        <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
            <input class="form-check-input" type="checkbox" value="{{ $option->value }}"
                {{ isset($option->checked) ? 'checked="checked"' : '' }} />
            <span class="form-check-label text-gray-600">{{ $option->label }}</span>
        </label>
        <!--end::Option-->
        @endforeach
    </div>
    <!--end::Options-->
</div>
<!--end::Input group-->
<!--begin::Input group-->
<div class="mb-5">
    <!--begin::Label-->
    <label class="form-label fs-5 fw-bold mb-3">{{ __('tables.Role') }}:</label>
    <!--end::Label-->
    <!--begin::Options-->
    <div class="d-flex flex-wrap fw-bold" id="filterRole">
        @foreach ($roleFilter as $option)
        <!--begin::Option-->
        <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
            <input class="form-check-input" type="checkbox" value="{{ $option->value }}"
                {{ isset($option->checked) ? 'checked="checked"' : '' }} />
            <span class="form-check-label text-gray-600">{{ $option->label }}</span>
        </label>
        <!--end::Option-->
        @endforeach
    </div>
    <!--end::Options-->
</div>
<!--end::Input group-->
<!--begin::Input group-->
<div class="mb-5 d-flex flex-stack">
    <!--begin::Label-->
    <label class="form-label fs-5 fw-bold">{{ __('tables.Name') }}:</label>
    <!--end::Label-->
    <!--begin::Input-->
    <input class="form-control w-150px" id="filterName" />
    <!--end::Input-->
</div>
<!--end::Input group-->
<!--begin::Input group-->
<div class="mb-5 d-flex flex-stack">
    <!--begin::Label-->
    <label class="form-label fs-5 fw-bold">{{ __('tables.Email') }}:</label>
    <!--end::Label-->
    <!--begin::Input-->
    <input class="form-control w-150px" id="filterEmail" />
    <!--end::Input-->
</div>
<!--end::Input group-->
<!--begin::Input group-->
<div class="mb-5 d-flex flex-stack">
    <!--begin::Label-->
    <label class="form-label fs-5 fw-bold">{{ __('tables.Google') }}:</label>
    <!--end::Label-->
    <!--begin::Input-->
    <div class="w-150px">
        <select class="form-select form-select-solid fw-bolder" data-kt-select2="true" data-hide-search="true"
            data-placeholder="Select option" data-allow-clear="true" id="filterGoogle"
            data-dropdown-parent="#toolbarFilter">
            <option></option>
            {!! $googleSelectOptions !!}
        </select>
    </div>
    <!--end::Input-->
</div>
<!--end::Input group-->
<!--begin::Input group-->
<div class="mb-10 d-flex flex-stack">
    <!--begin::Label-->
    <label class="form-label fs-5 fw-bold">{{ __('tables.Facebook') }}:</label>
    <!--end::Label-->
    <!--begin::Input-->
    <div class="w-150px">
        <select class="form-select form-select-solid fw-bolder" data-kt-select2="true" data-hide-search="true"
            data-placeholder="Select option" data-allow-clear="true" id="filterFacebook"
            data-dropdown-parent="#toolbarFilter">
            <option></option>
            {!! $facebookSelectOptions !!}
        </select>
    </div>
    <!--end::Input-->
</div>
<!--end::Input group-->
@endsection
