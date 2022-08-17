<!--begin::Modal - Adjust Balance-->
<div class="modal fade" id="export_modal" tabindex="-1" aria-hidden="true">
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header">
                <!--begin::Modal title-->
                <h2 class="fw-bolder">{{$exportTitle}}</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div id="export_close" class="btn btn-icon btn-sm btn-active-icon-primary">
                    <!--begin::Svg Icon | path: icons/duotone/Navigation/Close.svg-->
                    <span class="svg-icon svg-icon-1">
                        @svg('media/theme/svg/alert/close.svg', 'dismiss')
                    </span>
                    <!--end::Svg Icon-->
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <!--begin::Form-->
                <form id="export_form" class="form" method="post" action="{{ $exportRoute }}">
                    @csrf
                    @yield('export_content')
                    <div class="fv-row mb-10">
                        <!--begin::Label-->
                        <label class="fs-5 fw-bold form-label mb-5">{{__('tables.SelectExportFormat')}}:</label>
                        <!--end::Label-->
                        @exportFormat()
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="text-center">
                        <button type="reset" id="export_cancel"
                            class="btn btn-light me-3">{{__('general.Discard')}}</button>
                        <button type="submit" id="export_submit" class="btn btn-primary">
                            <span class="indicator-label">{{__('general.Submit')}}</span>
                            <span class="indicator-progress">{{__('general.PleaseWait')}}
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - New Card-->
