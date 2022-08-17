@extends('layouts.main')
@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid">
    @include('partials.page.toolbar')
    <!--begin::Post-->
    <div class="post d-flex flex-column-fluid">
        <!--begin::Container-->
        <div id="kt_content_container" class="container">
            <!--begin::Row-->
            <div class="row gy-5 g-xl-8">
                <!--begin::Col-->
                <div class="col-xxl-12">
                    <!--begin::Mixed Widget 2-->
                    <div class="card card-xxl-stretch">
                        <!--begin::Header-->
                        <div class="card-header border-0 bg-gray py-5">
                            <h3 class="card-title fw-bolder text-white">{{$page->title}}</h3>
                            <div class="card-toolbar"></div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Body-->
                        <div class="card-body pt-2">
                            <div class="d-flex align-items-center bg-light-warning rounded p-5 mb-7">
                                <!--begin::Icon-->
                                <span class="svg-icon svg-icon-warning me-5">
                                    @svg('media/theme/icons/duotone/general/user.svg')
                                </span>
                                <!--end::Icon-->
                                <!--begin::Title-->
                                <div class="flex-grow-1 me-2">
                                    <span class="fw-bolder text-gray-800 text-hover-primary fs-6">Pending users:</span>
                                </div>
                                <!--end::Title-->
                                <!--begin::Lable-->
                                <span class="fw-bolder py-1">{{$data->pendingUsersCount}}</span>
                                <!--end::Lable-->
                            </div>
                            <div class="d-flex align-items-center bg-light-success rounded p-5 mb-7">
                                <!--begin::Icon-->
                                <span class="svg-icon svg-icon-success me-5">
                                    @svg('media/theme/icons/duotone/general/user.svg')
                                </span>
                                <!--end::Icon-->
                                <!--begin::Title-->
                                <div class="flex-grow-1 me-2">
                                    <span class="fw-bolder text-gray-800 text-hover-primary fs-6">Active users:</span>
                                </div>
                                <!--end::Title-->
                                <!--begin::Lable-->
                                <span class="fw-bolder py-1">{{$data->activeUsersCount}}</span>
                                <!--end::Lable-->
                            </div>
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Mixed Widget 2-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->

        </div>
        <!--end::Container-->
    </div>
    <!--end::Post-->
</div>
<!--end::Content-->
@endsection
