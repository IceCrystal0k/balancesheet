@extends('layouts.main')
@section('styles')
<link href="{{ asset('vendors/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/jquery-ui/jquery-ui.structure.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('vendors/jquery-ui/jquery-ui.theme.min.css') }}" rel="stylesheet" type="text/css" />
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

                <div class="card-header flex-nowrap pt-5">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-dark">Applied filters</span>
                        <span id="filtersInfo" class="text-gray-700 pt-2 fw-semibold fs-6">{{ $data->filtersSummary }}</span>
                    </h3>
                    <!--end::Title-->
                </div>

                <!--begin::Card body-->
                <div class="card-body pt-5">
                    <h3 class="card-title">Daily Balance</h3>
                    <div class="chart daily">
                        <div class="chart-label credit">Credit: <strong>{{$data->daily->sumCredit}}</strong></div>
                        <div class="chart-bar credit"></div>
                        <div class="chart-label debit">Debit: <strong>{{$data->daily->sumDebit}}</strong></div>
                        <div class="chart-bar debit red"></div>
                        <div class="chart-label net">Net: <strong>{{$data->daily->sumNet}}</strong></div>
                        <div class="chart-bar net blue"></div>
                        <div class="ruler">
                            <span class="start"></span><em class="start"></em>
                            <span class="zero">0</span><em class="zero"></em>
                            <em class="end"></em><span class="end"></span>
                        </div>
                    </div>

                    <h3 class="card-title pt-15">Monthly Balance</h3>
                    <div class="chart monthly">
                        <div class="chart-label credit">Credit: <strong>{{$data->monthly->sumCredit}}</strong></div>
                        <div class="chart-bar credit"></div>
                        <div class="chart-label debit">Debit: <strong>{{$data->monthly->sumDebit}}</strong></div>
                        <div class="chart-bar debit red"></div>
                        <div class="chart-label net">Net: <strong>{{$data->monthly->sumNet}}</strong></div>
                        <div class="chart-bar net blue"></div>
                        <div class="ruler"><span class="start"></span><em class="start"></em>
                            <span class="zero">0</span><em class="zero"></em>
                            <em class="end"></em><span class="end"></span>
                        </div>
                    </div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
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
@endsection

@section('page_js_files')
<script>
    var dailySums = {!!json_encode($data->daily, JSON_HEX_TAG)!!};
    var monthlySums = {!!json_encode($data->monthly, JSON_HEX_TAG)!!};
    var userDateFormat = '{{ $userDateFormat }}';
</script>
<script src="{{ asset('js/app/'.$page->routePath.'/list.js') }}"></script>
@endsection
