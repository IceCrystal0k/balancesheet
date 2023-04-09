@extends('partials.page.filter', ['globalSearchPlaceholder' => __($page->translationPrefix.'SearchTable'),
'createNewLabel' =>
__($page->translationPrefix.'CreateNew')])
@section('filter_content')
<!--begin::Input group-->
<div class="mb-10 d-flex flex-stack">
    <!--begin::Label-->
    <label class="form-label fs-5 fw-bold">{{ __('tables.Name') }}:</label>
    <!--end::Label-->
    <!--begin::Input-->
    <input class="form-control w-250px" id="filterName" />
    <!--end::Input-->
</div>
<!--end::Input group-->
<!--begin::Input group-->
<div class="mb-10 d-flex flex-stack">
    <!--begin::Label-->
    <label class="form-label fs-5 fw-bold">{{ __('tables.Slug') }}:</label>
    <!--end::Label-->
    <!--begin::Input-->
    <input class="form-control w-250px" id="filterSlug" />
    <!--end::Input-->
</div>
<!--end::Input group-->
@endsection
