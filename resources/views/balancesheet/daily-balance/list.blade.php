@extends('layouts.main')
@section('styles')
<link href="{{ asset('vendors/datatables/datatables.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/datatables/plugins/dataTables.responsive.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/jquery-ui/jquery-ui.structure.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/jquery-ui/jquery-ui.theme.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid">
    @include('partials.page.toolbar', ['btn_create' => __($page->translationPrefix.'CreateNew')])
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid">
        <!--begin::Container-->
        <div id="kt_content_container" class="container">
            <!--begin::Card-->
            <div class="card">
                @include('errors.success')
                @include('errors.error')

                @include($page->viewPath.'.filter')

                <!--begin::Card body-->
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle table-row-dashed fs-6 gy-5"
                            id="daily-balance-table" data-route="{{ route($page->routePath.'/list') }}">
                            <thead>
                                <tr>
                                    <th class="w-10px pe-2">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                                            <input class="form-check-input" type="checkbox" data-kt-check="true"
                                                data-kt-check-target="#targets-table .form-check-input" value="1" />
                                        </div>
                                    </th>
                                    <th>{{ __('tables.Id') }}</th>
                                    <th>{{ __('tables.Date') }}</th>
                                    <th>{{ __('tables.Type') }}</th>
                                    <th>TypeIdHidden</th>
                                    <th>{{ __('tables.Product') }}</th>
                                    <th>{{ __('tables.Target') }}</th>
                                    <th>TargetIdHidden</th>
                                    <th>{{ __('tables.Amount') }}</th>
                                    <th>{{ __('tables.UnitPrice') }}</th>
                                    <th>{{ __('tables.Price') }}</th>
                                    <th>{{ __('tables.Actions') }}</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <td colspan="12">
                                    <div class="d-flex flex-stack">
                                        <span>{{__('tables.Income')}}: <span id="totalCredit"></span></span>
                                        <span>{{__('tables.Spending')}}: <span id="totalDebit"></span></span>
                                        <span>{{__('tables.Net')}}: <span id="totalNet"></span></span>
                                    </div>
                                </td>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <!--end::Card body-->
                @include('partials.form.delete_form')
                @include('partials.form.post_form')
            </div>
            <!--end::Card-->
            <!--begin::Modals-->
            @include($page->viewPath.'.export')
            <!--end::Modal - New Card-->
            <!--end::Modals-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->
@endsection

@section('vendor_js_files')
<script src="{{ asset('vendors/i18next/i18next.min.js') }}"></script>
<script src="{{ asset('vendors/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('vendors/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('vendors/daterangepicker/moment.min.js') }}"></script>
<script src="{{ asset('vendors/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('vendors/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('vendors/datatables/plugins/dataTables.responsive.min.js') }}"></script>
@endsection

@section('page_js_files')
<script src="{{ asset('js/app/balancesheet/services/product.js') }}"></script>
<script>var userDateFormat = '{{ $userDateFormat }}';</script>
<script src="{{ asset('js/app/'.$page->routePath.'/list.js') }}"></script>
@endsection
