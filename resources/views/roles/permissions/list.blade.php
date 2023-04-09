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
                        <input type="hidden" name="edit_id" id="edit_id" value="{{ $roleId }}" />
                        <!--begin::Card body-->
                        <div class="card-body border-top p-9">
                            @foreach ($permissionsList as $item)
                            <!--begin::Input group-->
                            <div class="d-flex justify-content-between">
                                <!--begin::Label-->
                                <label class="col-form-label fw-bold fs-6">{{ $item->name }}</label>
                                <!--end::Label-->
                                <!--begin::input-->
                                <div class="form-check form-check-solid form-switch">
                                    <input class="form-check-input w-45px h-30px" type="checkbox"
                                    id="permission_{{$item->id}}" name="permissions[]" value="{{$item->id}}"
                                        {{ $item->active ? 'checked="checked"' : '' }} />
                                    <label class="form-check-label" for="permission_{{$item->id}}"></label>
                                </div>
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->
                            @endforeach
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

@endsection
