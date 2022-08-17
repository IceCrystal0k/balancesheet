@unless ($breadcrumbs->isEmpty())
<!--begin::Breadcrumb-->
<ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
    @foreach ($breadcrumbs as $breadcrumb)

        @if (!is_null($breadcrumb->url) && !$loop->last)
    <li class="breadcrumb-item text-muted"><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-200 w-5px h-2px"></span>
    </li>
        @else
    <li class="breadcrumb-item text-dark">{{ $breadcrumb->title }}</li>
            @if (is_null($breadcrumb->url))
    <li class="breadcrumb-item">
        <span class="bullet bg-gray-200 w-5px h-2px"></span>
    </li>
            @endif
        @endif

    @endforeach
</ul>
<!--end::Breadcrumb-->
@endunless
