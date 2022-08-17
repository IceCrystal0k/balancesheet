<form action="" method="post" id="form-delete">
    @csrf
    <input type="hidden" name="_method" value="DELETE">
    <input type="hidden" name="deleteIds" id="deleteIds" value="">
    @if (isset($page->routeDeleteSelected))
    <input type="hidden" id="routeDeleteSelected" value="{{$page->routeDeleteSelected}}">
    @endif
</form>