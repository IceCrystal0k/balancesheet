@extends('partials.page.export', ['exportTitle' => __($page->translationPrefix.'ExportItems'), 'exportRoute' => route($page->routePath.'/export')])
@section('export_content')
<div class="row mb-6">
    <!--begin::Label-->
    <label class="col-lg-12 col-form-label fw-bold fs-6">
        <i class="bi bi-info-circle ms-1"></i> "{{ __('general.ExportFiltersInfo') }}
    </label>
    <!--end::Label-->
    <input type="hidden" id="exportFilters" name="exportFilters" value="" />
</div>
@endsection
