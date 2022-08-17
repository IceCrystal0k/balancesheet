@extends('partials.page.export', ['exportTitle' => __($page->translationPrefix.'ExportItems'), 'exportRoute' => route($page->routePath.'/export')])
@section('export_content')
@endsection
