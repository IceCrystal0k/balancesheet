@if(isset($row_div) && $row_div)<div class="row">@endif
<div class="{{ $col_class ?? 'col-md-6'}}" style="{{ $style ?? '' }}" id="{{ $col_id ?? ''   }}" {!! isset($tooltip) ? 'data-popup="tooltip" title="'.$tooltip.'" data-html="true"' : '' !!}>
    @if(isset($type) && $type == 'checkbox')
        <div class="form-group">
            <div class="form-check form-check-switchery form-check-inline">
                <label class="form-check-label">
                    <input {!! isset($disabled) && $disabled ? 'disabled="off"' : '' !!} {!! isset($data_target) ? 'data-target='.$data_target.'' : '' !!} type="checkbox" value="{{ $value ?? 0 }}" name="{{ $id }}" class="form-check-input-switchery" {{ $checked ? 'checked' : '' }} id="{{ $id }}">
                    {{ $placeholder }}
                </label>
            </div>
        </div>
    @elseif(isset($type) && $type == 'multiselect')
        <div class="form-group {{ $form_group_class ?? '' }}">
            <label for="{{ $id }}">{!! isset($required) && $required ? '<span class="text-danger">*</span> ' : '' !!}{{ $placeholder }}:</label>
            <select name="{{ $id }}{!! isset($multiple) && $multiple ? '[]' : '' !!}" {!! isset($disabled) && $disabled ? 'disabled="off"' : '' !!} id="{{ $id }}" class="form-control form-multiselect" {!! isset
            ($multiple) && $multiple ? 'multiple="true"' : '' !!} {!! isset($show_search) && $show_search ? 'data-enable-filtering="true" data-enable-case-insensitive-filtering="true"' : '' !!}>
                @foreach($items as $key => $name)
                    @if(isset($multiple) && $multiple)
                        <option value="{{ $key }}" {{ ($selected && in_array($key, $selected) || in_array($key, old($id, []))) && !is_null($selected) ? 'selected="selected"' : '' }}>
                            {{ $name }}
                        </option>
                    @else
                        <option value="{{ $key }}" {{ ($selected && $selected == $key || old($id) == $key) && $selected ? 'selected="selected"' : '' }}>
                            {{ $name }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
    @elseif(isset($type) && $type == 'select2')
        <div class="form-group {{ $form_group_class ?? '' }}">
            <label for="{{ $id }}">{!! isset($required) && $required ? '<span class="text-danger">*</span> ' : '' !!}{{ $placeholder }}:</label>
            <select name="{{ $id }}{!! isset($multiple) && $multiple ? '[]' : '' !!}" id="{{ $id }}" class="form-control form-select2 {{ $append_class ?? '' }}" {!! isset($multiple) && $multiple ? 'multiple="true"' : '' !!} {!! isset($show_search) && !$show_search ? 'data-minimum-results-for-search="-1"' : '' !!} {!! isset($allow_clear) ? 'data-allow-clear="1"' : '' !!} {!! isset($disabled) && $disabled ? 'disabled="off"' : '' !!}>

                @if (isset($choose) && $choose)
                    <option></option>
                @endif

                @if(is_a($items, 'Illuminate\Database\Eloquent\Collection'))
                    @foreach($items as $key => $item)
                        <?php

                        $data_props_print = [];

                        if (isset($data_props) && is_array($data_props) && count($data_props) > 0) {
                            foreach ($data_props as $data_prop_key => $data_prop_value) {
                                $data_props_print[] = 'data-'.$data_prop_key.'="'.$item[$data_prop_value].'"';
                            }
                        }

                        $optionValue = $item[($value_attr ?? 'id')];
                        $optionName = $item[($name_attr ?? 'name')];

                        ?>
                        @if(isset($multiple) && $multiple)
                            <option value="{{ $optionValue }}" {{ ($selected && in_array($optionValue, $selected) || in_array($optionValue, old($id, []))) && !is_null($selected) ? 'selected="selected"' : '' }}>
                                {{ $optionName }}
                            </option>
                        @else
                            <option value="{{ $optionValue }}" {{ ($selected && $selected == $optionValue || old($id) == $optionValue) && !is_null($selected) ? 'selected="selected"' : '' }} {!! implode(' ', $data_props_print); !!}>
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
    @elseif(isset($type) && $type == 'select')
        <div class="form-group">
            <label for="{{ $id }}">{!! isset($required) && $required ? '<span class="text-danger">*</span> ' : '' !!}{{ $placeholder }}</label>

            <select name="{{ $id }}{!! isset($multiple) && $multiple ? '[]' : '' !!}" id="{{ $id }}" class="form-control" {!! isset($multiple) && $multiple ? 'multiple="true"' : '' !!} {!! isset($disabled) && $disabled ? 'disabled="off"' : '' !!}>
            @foreach($items as $key => $name)
                @if(isset($multiple) && $multiple)
                    <option value="{{ $key }}" {{ $selected && in_array($key, $selected) || in_array($key, old($id, [])) ? 'selected="selected"' : '' }}>
                        {{ $name }}
                    </option>
                @else
                    <option value="{{ $key }}" {{ $selected && $selected == $key || old($id) == $key ? 'selected="selected"' : '' }}>
                        {{ $name }}
                    </option>
                @endif
            @endforeach
            </select>
        </div>
    @elseif(isset($type) && $type == 'dual_listboxes')
        <div class="form-group">
            <select name="{{ $id }}[]" id="{{ $id }}" {!! isset($disabled) && $disabled ? 'disabled="disabled"' : '' !!} class="form-control {{ $select_class ?? null }} listbox-filtered-results" multiple="multiple">
                @foreach($items as $key => $item)
                    <option value="{{ $key }}" {!! $selected && in_array($key, $selected) || in_array($key, old($id, [])) ? 'selected' : '' !!}>
                        {{ $item }}
                    </option>
                @endforeach
            </select>
        </div>
    @elseif(isset($type) && $type == 'datepicker')
        <div class="form-group form-group-float">
            <label for="{{ $id }}" class="form-group-float-label">{!! isset($required) && $required ? '<span class="text-danger">*</span> ' : '' !!}{{ $placeholder }}:</label>

            <div class="input-group">
                <span class="input-group-prepend">
                    <span class="input-group-text"><i class="icon-calendar22"></i></span>
                </span>
                <input type="text" autocomplete="off" data-date-format="Y-m-d" class="form-control pickadate" name="{{
                $id }}" id="{{
                $id }}" placeholder="{{ $placeholder }}" value="{!! isset($value) ? date('d/m/Y', strtotime($value)) : null !!}">
            </div>
        </div>
    @elseif(isset($type) && $type == 'datetimepicker')
        <div class="form-group {{ $form_group_class ?? '' }}">
            <label for="{{ $id }}">{!! isset($required) && $required ? '<span class="text-danger">*</span> ' : '' !!}{{ $placeholder }}:</label>
            <div class="form-group-feedback form-group-feedback-right">
                <input type="text" data-date-format="{{ $date_format ?? 'YYYY-MM-DD' }}" {{ isset($min_date) ? 'data-date-min-date="'.$min_date.'"' : '' }} {{ isset($max_date) ? 'data-date-max-date="'.$max_date.'"' : '' }} name="{{ $id }}" id="{{ $id
                }}" class="form-control datetimepicker" placeholder="{{ $placeholder }}" autocomplete="off" value="{{
                old($id,
                $value) }}" {!! isset($disabled) && $disabled ? 'disabled="off"' : '' !!}
                {{ isset($view_mode) ? 'data-date-view-mode='.$view_mode.'' : '' }}>
                <div class="form-control-feedback">
                    <i class="icon-calendar"></i>
                </div>
            </div>
            @if (isset($helper))
                <div class="d-block form-text text-left">
                    <span class="badge badge-danger text-center {{ $helper['class'] }}">{{ $helper['message'] }}</span>
                </div>
            @endif
        </div>
    @elseif(isset($type) && $type == 'datetimepicker_dropdown')
        <div class="form-group {{ $form_group_class ?? '' }}">
            <div class="dropdown" data-id-start="{{ $id }}" data-id-end="{{ $id_end ?? null }}">
                <label data-toggle="dropdown" class="dropdown-toggle">{!! isset($required) && $required ? '<span class="text-danger">*</span> ' : '' !!}{{ $placeholder }}</label>
                <div class="dropdown-menu trigger-preset-time" aria-labelledby="dropdownMenu">
                    <a href="#" class="dropdown-item" data-time="today">{{ ___('content.general.today', 'Today') }}</a>
                    <a href="#" class="dropdown-item" data-time="yesterday">{{ ___('content.general.yesterday', 'Yesterday') }}</a>
                    @if (isset($id_end))
                    <a href="#" class="dropdown-item" data-time="this_month">{{ ___('content.general.this_month', 'This month') }}</a>
                    <a href="#" class="dropdown-item" data-time="last_month">{{ ___('content.general.last_month', 'Last month') }}</a>
                    @endif
                </div>
            </div>
            <div class="form-group-feedback form-group-feedback-right">
                <input type="text" data-date-format="{{ $date_format ?? 'YYYY-MM-DD' }}" {{ isset($min_date) ? 'data-date-min-date="'.$min_date.'"' : '' }} {{ isset($max_date) ? 'data-date-max-date="'.$max_date.'"' : '' }} name="{{ $id }}" id="{{ $id
                }}" class="form-control datetimepicker" placeholder="{{ $placeholder }}" autocomplete="off" value="{{
                old($id, $value) }}" {!! isset($disabled) && $disabled ? 'disabled="off"' : '' !!}
                {{ isset($view_mode) ? 'data-date-view-mode='.$view_mode.'' : '' }}>
                <div class="form-control-feedback">
                    <i class="icon-calendar"></i>
                </div>
            </div>
            @if (isset($helper))
                <div class="d-block form-text text-left">
                    <span class="badge badge-danger text-center {{ $helper['class'] }}">{{ $helper['message'] }}</span>
                </div>
            @endif
        </div>
    @elseif(isset($type) && $type == 'file')
        <div class="form-group">
            <label for="{{ $id }}">{!! isset($required) && $required ? '<span class="text-danger">*</span> ' : '' !!}{{ $placeholder }}:</label>
            <input type="file" class="form-control" name="{{ $id }}" id="{{ $id }}" placeholder="{{ $placeholder }}">
        </div>
    @elseif(isset($type) && $type == 'textarea')
        <div class="form-group">
            <label for="{{ $id }}">{!! isset($required) && $required ? '<span class="text-danger">*</span> ' : '' !!}{{ $placeholder }}:</label>

            <textarea class="form-control" name="{{ $id }}" id="{{ $id }}" placeholder="{{ $placeholder }}" rows="{{ $rows ?? 3 }}">{{ old($id, $value) }}</textarea>

            @if(isset($right_text))
                <span class="form-text text-muted text-left">{!! $right_text !!}</span>
            @endif
        </div>
    @else
        <div class="form-group {{ $form_group_class ?? '' }}">
            <label for="{{ $id }}">{!! isset($required) && $required ? '<span class="text-danger">*</span> ' : '' !!}{!! $label ?? $placeholder !!}:</label>

            @if(isset($right_icon) || isset($right_text) || isset($right_button))
                <div class="input-group">
            @endif

                <input type="{{ $type ?? 'text' }}" class="form-control" name="{{ $fieldName ?? $id }}" id="{{ $id }}" placeholder="{{ $placeholder }}" value="{{ old($id, $value) }}" {!! isset($autocomplete) ? 'autocomplete="off"' : '' !!} {!! isset($disabled) && $disabled ? 'disabled="off"' : '' !!} {!! isset($step) ? 'step="'.$step.'"' : '' !!} {!! isset($min) ? 'min="'.$min.'"' : '' !!} {!! isset($max) ? 'max="'.$max.'"' : '' !!} {!! isset($required) ? 'required' : '' !!} {!! isset($max_length) ? 'maxlength="'.$max_length.'"' : '' !!}>

            @if(isset($right_icon))
                    <span class="input-group-append">
                        <span class="input-group-text"><i class="{{ $right_icon }}"></i></span>
                    </span>
                </div>
            @endif

            @if(isset($right_text))
                <span class="input-group-append ml-1">
                        <span class="input-group-text">{{ $right_text }}</span>
                    </span>
                </div>
            @endif

            @if(isset($right_button))
                    <span class="input-group-append ml-1">
                        <a class="btn {{ $right_button['class'] }}" href="{{ $right_button['href'] }}">
                            {!! $right_button['text'] !!}
                        </a>
                    </span>
                </div>
            @endif

        </div>
    @endif
</div>
@if(isset($row_div) && $row_div)</div>@endif
