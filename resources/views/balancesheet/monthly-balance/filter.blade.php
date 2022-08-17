@extends('partials.page.filter', ['globalSearchPlaceholder' => __($page->translationPrefix.'SearchTable'), 'createNewLabel' =>
__($page->translationPrefix.'CreateNew'), 'hideGlobalFilter' => true])
@section('filter_content')
<!--begin::Input group-->
<div class="mb-5 d-flex flex-stack">
    <!--begin::Label-->
    <label class="form-label fs-5 fw-bold">{{ __('tables.Product') }}:</label>
    <!--end::Label-->
    <!--begin::Input-->
    <input class="form-control w-250px" id="filterProduct" autocomplete="off" placeholder="{{__($page->translationPrefix.'ProductName')}}" />
    <!--end::Input-->
</div>
<!--end::Input group-->
<!--begin::Input group-->
<div class="mb-5 d-flex flex-stack">
    <!--begin::Label-->
    <label class="form-label fs-5 fw-bold">{{ __('tables.Price') }}:</label>
    <!--end::Label-->
    <!--begin::Input-->
    <input class="form-control w-150px" id="filterPriceMin" placeholder="{{__($page->translationPrefix.'PriceMin')}}" />
    <!--end::Input-->
    <!--begin::Input-->
    <input class="form-control w-150px" id="filterPriceMax" placeholder="{{__($page->translationPrefix.'PriceMax')}}" />
    <!--end::Input-->
</div>
<!--end::Input group-->
<!--begin::Input group-->
<div class="mb-5 d-flex flex-stack">
    <!--begin::Label-->
    <label class="form-label fs-5 fw-bold">{{ __('tables.Year') }}:</label>
    <!--end::Label-->
    <!--begin::Col-->
    <div class="w-150px">
        <select id="filterYearMin" aria-label="{{ __($page->translationPrefix.'YearMin') }}" data-control="select2"
            data-placeholder="{{ __($page->translationPrefix.'YearMin') }}"
            data-hide-search="true"
            class="form-select form-select-solid form-select-lg">
            <option value="">{{ __($page->translationPrefix.'YearMin') }}</option>
            {!! $yearSelectOptions !!}
        </select>
    </div>
    <!--end::Col-->
    <!--begin::Col-->
    <div class="w-150px">
        <select id="filterYearMax" aria-label="{{ __($page->translationPrefix.'YearMax') }}" data-control="select2"
            data-placeholder="{{ __($page->translationPrefix.'YearMax') }}"
            data-hide-search="true"
            class="form-select form-select-solid form-select-lg">
            <option value="">{{ __($page->translationPrefix.'YearMax') }}</option>
            {!! $yearSelectOptions !!}
        </select>
    </div>
    <!--end::Col-->
</div>
<!--end::Input group-->
<!--begin::Input group-->
<div class="mb-5 d-flex flex-stack">
    <!--begin::Label-->
    <label class="form-label fs-5 fw-bold">{{ __('tables.Month') }}:</label>
    <!--end::Label-->
    <!--begin::Col-->
    <div class="w-150px">
        <select id="filterMonthMin" aria-label="{{ __($page->translationPrefix.'MonthMin') }}" data-control="select2"
            data-placeholder="{{ __($page->translationPrefix.'MonthMin') }}"
            data-hide-search="true"
            class="form-select form-select-solid form-select-lg">
            <option value="">{{ __($page->translationPrefix.'MonthMin') }}</option>
            {!! $monthSelectOptions !!}
        </select>
    </div>
    <!--end::Col-->
    <!--begin::Col-->
    <div class="w-150px">
        <select id="filterMonthMax" aria-label="{{ __($page->translationPrefix.'MonthMax') }}" data-control="select2"
            data-placeholder="{{ __($page->translationPrefix.'MonthMax') }}"
            data-hide-search="true"
            class="form-select form-select-solid form-select-lg">
            <option value="">{{ __($page->translationPrefix.'MonthMax') }}</option>
            {!! $monthSelectOptions !!}
        </select>
    </div>
    <!--end::Col-->
</div>
<!--end::Input group-->
<!--begin::Input group-->
<div class="mb-5 d-flex flex-stack">
    <!--begin::Label-->
    <label class="form-label fs-5 fw-bold">{{ __('tables.Target') }}:</label>
    <!--end::Label-->
    <!--begin::Input-->
    <div class="w-250px">
        <select id="filterTarget" aria-label="{{ __($page->translationPrefix.'SelectTarget') }}" data-control="select2"
            data-placeholder="{{ __($page->translationPrefix.'SelectTarget') }}"
            data-hide-search="true"
            class="form-select form-select-solid form-select-lg w-250px">
            <option value="">{{ __($page->translationPrefix.'SelectTarget') }}</option>
            {!! $targetSelectOptions !!}
        </select>
    </div>
    <!--end::Input-->
</div>
<!--end::Input group-->
<!--begin::Input group-->
<div class="mb-5 d-flex flex-stack">
    <!--begin::Label-->
    <label class="form-label fs-5 fw-bold">{{ __('tables.Type') }}:</label>
    <!--end::Label-->
    <!--begin::Input-->
    <div class="w-250px">
        <select id="filterBalanceType" aria-label="{{ __('tables.Type') }}" data-control="select2"
            data-placeholder="{{ __('tables.Type') }}"
            data-hide-search="true"
            class="form-select form-select-solid form-select-lg">
            <option value="">{{ __('tables.Type') }}</option>
            {!! $balanceTypeSelectOptions !!}
        </select>
    </div>
    <!--end::Input-->
</div>
<!--end::Input group-->
@endsection
