<table class="table table-bordered align-middle table-row-dashed fs-6 gy-5">
    <thead>
        <tr>
            <th>{{ __('tables.Id') }}</th>
            <th>{{ __('tables.Year') }}</th>
            <th>{{ __('tables.Month') }}</th>
            <th>{{ __('tables.Type') }}</th>
            <th>{{ __('tables.Product') }}</th>
            <th>{{ __('tables.Target') }}</th>
            <th>{{ __('tables.Amount') }}</th>
            <th>{{ __('tables.UnitPrice') }}</th>
            <th>{{ __('tables.Price') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
        <tr>
            <td>{{ $row->id}}</td>
            <td>{{ $row->year}}</td>
            <td>{{ $row->month}}</td>
            <td>{{ $row->type_name}}</td>
            <td>{{ $row->product_name}}</td>
            <td>{{ $row->target_name}}</td>
            <td>{{ $row->amount}}</td>
            <td>{{ $row->unit_price}}</td>
            <td>{{ $row->price}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
