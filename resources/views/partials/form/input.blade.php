@if(isset($type) && $type == 'select2')
<div class="form-group {{ $form_group_class ?? '' }}">
    <label for="{{ $id }}">{!! isset($required) && $required ? '<span class="text-danger">*</span> ' : ''
        !!}{{ $placeholder }}:</label>
    <select name="{{ $id }}{!! isset($multiple) && $multiple ? '[]' : '' !!}" id="{{ $id }}"
        class="form-control form-select2 {{ $append_class ?? '' }}" {!! isset($multiple) && $multiple
        ? 'multiple="true"' : '' !!} {!! isset($show_search) && !$show_search ? 'data-minimum-results-for-search="-1"'
        : '' !!} {!! isset($allow_clear) ? 'data-allow-clear="1"' : '' !!} {!! isset($disabled) && $disabled
        ? 'disabled="off"' : '' !!}>

        @if (isset($choose) && $choose)
        <option></option>
        @endif

        @if(is_a($items, 'Illuminate\Database\Eloquent\Collection'))
        @foreach($items as $key => $item)
        <?php

$data_props_print = [];

if (isset($data_props) && is_array($data_props) && count($data_props) > 0) {
    foreach ($data_props as $data_prop_key => $data_prop_value) {
        $data_props_print[] = 'data-' . $data_prop_key . '="' . $item[$data_prop_value] . '"';
    }
}

$optionValue = $item[($value_attr ?? 'id')];
$optionName = $item[($name_attr ?? 'name')];

?>
        @if(isset($multiple) && $multiple)
        <option value="{{ $optionValue }}"
            {{ ($selected && in_array($optionValue, $selected) || in_array($optionValue, old($id, []))) && !is_null($selected) ? 'selected="selected"' : '' }}>
            {{ $optionName }}
        </option>
        @else
        <option value="{{ $optionValue }}"
            {{ ($selected && $selected == $optionValue || old($id) == $optionValue) && !is_null($selected) ? 'selected="selected"' : '' }}
            {!! implode(' ', $data_props_print); !!}>
                        {{ $optionName }}
                    </option>
                @endif
            @endforeach
        @else
            @foreach($items as $key => $name)
                @if(isset($multiple) && $multiple)
                    <option value="{{ $key }}" {{ ($selected && in_array($key, $selected) || in_array($key, old($id, []))) && !is_null($selected) ? 'selected="selected"' : '' }}>
                        {{ $name }}
                    </option>
                @else
      
              <option value="{{ $key }}" {{ ($selected && $selected == $key || old($id) == $key) && !is_null($selected) ? 'selected="selected"' : '' }}>
                        {{ $name }}
                    </option>
                @endif
            @endforeach
        @endif
    </select>
</div>
@endif