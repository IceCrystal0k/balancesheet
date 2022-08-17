@extends('partials.page.export', ['exportTitle' => __($page->translationPrefix.'ExportItems'), 'exportRoute' => route($page->routePath.'/export')])
@section('export_content')
<!--begin::Row-->
<div class="row fv-row mb-15">
    <!--begin::Label-->
    <label class="fs-5 fw-bold form-label mb-5">{{ __('tables.Status') }}:</label>
    <!--end::Label-->
    <!--begin::Options-->
    <div class="d-flex flex-wrap fw-bold" data-kt-table-export="export-status">
        @foreach ($statusFilter as $option)
        <!--begin::Option-->
        <label class="form-check form-check-sm form-check-custom form-check-solid mb-3 me-5">
            <input class="form-check-input" name="export_status[]" type="checkbox" value="{{ $option->value }}"
                {{ isset($option->checked) ? 'checked="checked"' : '' }} />
            <span class="form-check-label text-gray-600">{{ $option->label }}</span>
        </label>
        <!--end::Option-->
        @endforeach
    </div>
    <!--end::Options-->
    <!--end::Input group-->
</div>
<!--end::Row-->
@endsection
