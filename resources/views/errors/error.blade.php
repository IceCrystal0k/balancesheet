@if($errors->any())
<!--begin::Alert-->
<div class="alert alert-dismissible bg-danger d-flex flex-column flex-sm-row p-5 mb-10">
    <!--begin::Icon-->
    <span class="svg-icon svg-icon-2hx svg-icon-light me-4 mb-5 mb-sm-0">
        @svg('media/theme/svg/alert/pen-tool.svg', 'error')
    </span>
    <!--end::Icon-->

    <!--begin::Wrapper-->
    <div class="d-flex flex-column text-dark pe-0 pe-sm-10">
        <!--begin::Title-->
        <h4 class="mb-2 light">Error</h4>
        <!--end::Title-->

        <!--begin::Content-->
        <ul>@foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <!--end::Content-->
    </div>
    <!--end::Wrapper-->

    <!--begin::Close-->
    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
        data-bs-dismiss="alert">
        <span class="svg-icon svg-icon-2x svg-icon-light">
            @svg('media/theme/svg/alert/close.svg', 'dismiss')
        </span>
    </button>
    <!--end::Close-->
</div>
<!--end::Alert-->
@endif
