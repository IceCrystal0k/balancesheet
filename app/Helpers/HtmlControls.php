<?php
namespace App\Helpers;

use ErrorException;

class HtmlControls
{
    /**
     * Generate the html for a select control, from the given $records
     * @param {array} $records : array of objects to display
     * @param {array} $options: array with options :
     *             [ 'value'=>  selected value(s) -> string or array, if multi select,
     *               'valueField' => the field name which will be used for the option value
     *               'textField' => the field name which will be used for the option text
     *               'attribute' => [ expression => string, fields => array of fields names from the record ]
     *              ]
     *
     * Example: GenerateDropDownList($records, ['value' => 1, 'valueField'=>'id', 'textField' => 'name',
     *          'attribute' => ['expression' => 'custom-attribute1="%s" custom-attribute2="%s"', 'fields' => ['email', 'phone']  ] ])
     *  -> this will generate a string with options of form:
     *    <option value="$record[$valueField]" custom-attribute1="$record['email']" custom-attribute2="$record['phone']">$record[$textField]</option>
     */
    public static function GenerateDropDownList($records, $options)
    {
        $controlData = '';
        if (!$records) {
            return $controlData;
        }
        if (!isset($options['valueField']) || !isset($options['textField'])) {
            throw new ErrorException('Mandatory options attributes "valueField" and "textField" must be present');
        }

        if (count($records) > 0) {
            foreach ($records as $recordItem) {
                if (isset($options['value'])) {
                    if (is_array($options['value'])) {
                        $selectedAttr = (in_array($recordItem->{$options['valueField']}, $options['values'])) ? ' selected="selected" ' : '';
                    } else {
                        $selectedAttr = ($recordItem->{$options['valueField']} === $options['value']) ? ' selected="selected" ' : '';
                    }
                } else {
                    $selectedAttr = '';
                }

                // check if any attribute specified
                if (isset($options['attribute'])) {
                    if (isset($options['attribute']['fields'])) {
                        $args = [];
                        foreach ($options['attribute']['fields'] as $field) {
                            array_push($args, $recordItem->{$field});
                        }
                        $attribute = vsprintf($options['attribute']['expression'], $args);
                    } else {
                        $attribute = $options['attribute'];
                    }
                    $attribute .= ' '; // add a space after the attribute
                } else {
                    $attribute = '';
                }

                $controlData .= '<option ' . $attribute . 'value="' . $recordItem->{$options['valueField']} . '"' . $selectedAttr . '>' . $recordItem->{$options['textField']} . '</option>';
            }
        }
        return $controlData;
    }

    /**
     * get the html content for action column for datatable
     * @param {array} $actions - list with the actions
     *              $optionalRoutes - array with optional routes:
     *                  'delete' -> soft delete
     *                  'remove' - route for completly remove item
     *                  'activate' -> activate
     */
    private static function GetActionColumnContent($actions)
    {
        $content = '<a href="#" class="btn btn-sm btn-light btn-active-light-primary"
            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">' . __('tables.Actions') . '
            <span class="svg-icon svg-icon-5 m-0">
                <svg xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                    viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <path
                            d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z"
                            fill="#000000" fill-rule="nonzero"
                            transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)" />
                    </g>
                </svg>
            </span>
            <!--end::Svg Icon-->
        </a>
        <!--begin::Menu-->
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4"
            data-kt-menu="true">';

        foreach ($actions as $action) {
            $href = $action['method'] === 'get' ? 'href="' . $action['route'] . '"' : 'href="#" data-route="' . $action['route'] . '"';
            $content .= '<!--begin::Menu item-->
            <div class="menu-item px-3">
                <a ' . $href . ' class="menu-link px-3 ' . $action['css'] . '">' . $action['label'] . '</a>
            </div>
            <!--end::Menu item-->';
        }

        $content .= '
        </div>
        <!--end::Menu-->';

        return $content;
    }

    /**
     * get a list with actions
     * @param {string} $path : route path
     * @param {number} $id : item id
     * @param {string} $actions : a string with the desired actions, separated by comma : 'edit,activate,deactivate,delete,remove'
     *                          specify 'all' to get all actions
     * @param {array} $customActions : a list of custom actions to add to the list { route, css, label, method }
     */
    private static function GetAvailableActionList($path, $id, $actions, $customActions = null)
    {
        // creates an associative array with the actions
        $actionList = explode(',', $actions);
        foreach ($actionList as $key => $val) {
            $actionList[trim($val)] = 1;
        }

        $list = [];
        if (isset($actionList['all']) || isset($actionList['edit'])) {
            $list['edit'] = ['route' => route($path . '/edit', ['id' => $id]), 'css' => '', 'label' => __('tables.Edit'), 'method' => 'get'];
        }
        if (isset($actionList['all']) || isset($actionList['activate'])) {
            $list['activate'] = ['route' => route($path . '/activate', ['id' => $id]), 'css' => 'item-activate', 'label' => __('tables.Activate'), 'method' => 'post'];
        }
        if (isset($actionList['all']) || isset($actionList['deactivate'])) {
            $list['deactivate'] = ['route' => route($path . '/deactivate', ['id' => $id]), 'css' => 'item-deactivate', 'label' => __('tables.Deactivate'), 'method' => 'post'];
        }
        if (isset($actionList['all']) || isset($actionList['hide'])) {
            $list['hide'] = ['route' => route($path . '/hide', ['id' => $id]), 'css' => 'item-hide', 'label' => __('tables.Hide'), 'method' => 'post'];
        }
        if (isset($actionList['all']) || isset($actionList['delete'])) {
            $list['bank'] = ['route' => route($path . '/delete', ['id' => $id]), 'css' => 'item-delete', 'label' => __('tables.Delete'), 'method' => 'delete'];
        }
        if (isset($actionList['all']) || isset($actionList['remove'])) {
            $list['remove'] = ['route' => route($path . '/remove', ['id' => $id]), 'css' => 'item-remove', 'label' => __('tables.Remove'), 'method' => 'delete'];
        }
        if ($customActions) {
            foreach ($customActions as $action) {
                $list[$action->name] = ['route' => $action->route, 'css' => $action->css, 'label' => $action->label, 'method' => $action->method];
            }
        }

        return $list;
    }

    /**
     * get a list with actions
     * @param {string} $path : path for route
     * @param {object} $item : path for route
     * @param {string} $actions : a string with the desired actions, separated by comma : 'edit,activate,deactivate,delete,remove'
     *                          specify 'all' to get all actions
     * @param {array} $customActions : a list of custom actions to add to the list { route, css, label, method }
     */
    public static function GetActionColumn($path, $item, $actions, $customActions = null)
    {
        $actionList = HtmlControls::GetAvailableActionList($path, $item->id, $actions, $customActions);
        return HtmlControls::GetActionColumnContent($actionList);
    }

    public static function GetSelectRowColumn($id)
    {
        return '<div class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input" name="selection[]" id="' . $id . '" type="checkbox" value="1" />
                </div>';
    }

    public static function GetBooleanControl($val)
    {
        if (!$val) {
            return '';
        }
        return '<div class="form-check form-check-custom form-check-solid">
                <span class="form-check-input"></span>
            </div>';
    }

    /**
     * transforms an associative array to an array of objects  [{ label, value, checked }]
     */
    public static function ArrayToCheckedList($array, $translationPrefix, $selectedValues = null)
    {
        $list = [];
        foreach ($array as $key => $val) {
            $item = (object) ['label' => __($translationPrefix . $val), 'value' => $key];
            if ($selectedValues && in_array($key, $selectedValues)) {
                $item->checked = 1;
            }
            array_push($list, $item);
        }
        return $list;
    }

    /**
     * transforms an associative array to an array of objects used for select2
     * set the value for select 2 to be the key of the array and the label for select 2 to be the value of the array
     */
    public static function ArrayToSelectOptions($array, $translationPrefix)
    {
        $list = [];
        foreach ($array as $key => $val) {
            $item = (object) ['label' => __($translationPrefix . $val), 'value' => $key];
            // dd($item);
            array_push($list, $item);
        }
        return $list;
    }

    public static function GetCategoryItemActions($id)
    {
        return '<a href="' . route('categories/edit', ['id' => $id]) . '" class="category-icon item-edit"><i class="bi bi-pencil" data-bs-toggle="tooltip" title="' . __('category.Edit') . '"></i></a>' .
        '<a href="#" data-route="' . route('categories/delete', ['id' => $id]) . '" class="category-icon item-delete"><i class="bi bi-x" data-bs-toggle="tooltip" title="' . __('category.Delete') . '"></i></a>';
    }

    public static function GetPdfDownloadAction($route)
    {
        return '<a href="' . $route . '" class="item-download"><i class="bi bi-file-pdf" data-bs-toggle="tooltip" title="' . __('tables.DownloadPdf') . '"></i></a>';
    }

    /**
     * Generate the html for years select control, for the given attributes
     * @param {number} $yearsMinus : how many years to susbtract from current year
     * @param {number} $yearsPlus : how many years to add to current year
     * @param {number} $selectedValue which value to select
     */
    public static function GenerateDropDownListYear($yearsMinus, $yearsPlus, $selectedValue)
    {
        $startYear = (int) date("Y", mktime(0, 0, 0, date("m"), date("d"), date("Y") - $yearsMinus));
        $endYear = (int) date("Y", mktime(0, 0, 0, date("m"), date("d"), date("Y") + $yearsPlus));

        $records = array();
        for ($year = $startYear; $year <= $endYear; $year++) {
            $recordItem = new DropDownListItem();
            $recordItem->value = $year;
            $recordItem->text = $year;
            array_push($records, $recordItem);
        }

        $options = ['value' => $selectedValue, 'valueField' => 'value', 'textField' => 'text'];
        return self::GenerateDropDownList($records, $options);
    }

    /**
     * Generate the html for months select control
     * @param {number} $selectedValue which value to select
     */
    public static function GenerateDropDownListMonth($selectedValue)
    {
        $monthList = array("jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sep", "oct", "nov", "dec");

        $records = array();
        $monthIndex = 1;
        foreach ($monthList as $month) {
            $recordItem = new DropDownListItem();
            $recordItem->value = $monthIndex; //sprintf("%02d", $nMonth);
            $recordItem->text = __('general.months.' . $month);
            array_push($records, $recordItem);
            $monthIndex++;
        }

        $options = ['value' => $selectedValue, 'valueField' => 'value', 'textField' => 'text'];
        return self::GenerateDropDownList($records, $options);
    }

    public static function GenerateDropDownListNumbers($startNumber, $endNumber, $formatNumber = false, $selectedValue = '')
    {
        $records = array();
        // example of format: $formatNumber = "%02d";

        for ($nIndex = $startNumber; $nIndex < $endNumber; $nIndex++) {
            $recordItem = new DropDownListItem();
            $recordItem->value = ($formatNumber) ? sprintf($formatNumber, $nIndex) : $nIndex;
            $recordItem->text = ($formatNumber) ? sprintf($formatNumber, $nIndex) : $nIndex;
            array_push($records, $recordItem);
        }

        return HtmlControls::GenerateDropDownList($records, "value", "text", $selectedValue);
    }

    public static function GenerateDropDownListHoursHalf($startNumber, $endNumber, $selectedValue = '')
    {
        $rows = array();
        $formatNumber = "%02d:%02d";

        for ($index = $startNumber; $index <= $endNumber; $index++) {
            $row = new DropDownListItem();
            $number = ($index == 24) ? 0 : $index;
            $row->value = sprintf($formatNumber, $number, 0);
            $row->text = sprintf($formatNumber, $number, 0);
            array_push($rows, $row);

            if ($index != $endNumber) {
                $row = new DropDownListItem();
                $row->value = sprintf($formatNumber, $number, 30);
                $row->text = sprintf($formatNumber, $number, 30);
                array_push($rows, $row);
            }
        }

        return HtmlControls::GenerateDropDownList($rows, "value", "text", $selectedValue);
    }

    public static function GenerateDropDownListStrings($itemsText, $itemsValue, $selectedValue, $selectedValues = null)
    {
        $records = array();
        $itemsCount = count($itemsText);
        for ($nIndex = 0; $nIndex < $itemsCount; $nIndex++) {
            // if no value passed, use the text as value
            $itemValue = ($itemsValue == null) ? $itemsText[$nIndex] : $itemsValue[$nIndex];
            $recordItem = new DropDownListItem($itemsText[$nIndex], $itemValue);
            array_push($records, $recordItem);
        }

        return HtmlControls::GenerateDropDownList($records, "value", "text", $selectedValue, $selectedValues);
    }

    public static function GenerateDropDownListBoolean($selectedValue)
    {
        $records = array();
        $item = new DropDownListItem(__('Yes'), '1');
        array_push($records, $item);
        $item = new DropDownListItem(__('No'), '0');
        array_push($records, $item);

        return HtmlControls::GenerateDropDownList($records, ['value' => $selectedValue, 'valueField' => 'value', 'textField' => 'text']);
    }

    public static function MultiplyContent($count, $data)
    {
        $ret = '';
        for ($rowIndex = 0; $rowIndex < $count; $rowIndex++) {
            $ret .= $data;
        }
        return $ret;
    }

    // $controlName - i.e.: chkPage -> will generate ids chkPage_1, chkPage_2 ....; group name will be chkPage[]
    // $cssClass - required for check all;
    public static function GenerateCheckList($arrLabels, $arrSelectedValues, $controlName, $cssClass = '', $addCheckAll = false)
    {
        $ret = '';
        $rowIndex = 1;
        if ($cssClass != '') {
            $cssClassAdd = ' class="' . $cssClass . '"';
        }

        foreach ($arrLabels as $label) {
            if ($arrSelectedValues == null) {
                $checkedStatus = '';
            } else {
                $checkedStatus = (in_array($rowIndex, $arrSelectedValues)) ? 'checked="checked"' : '';
            }

            $ret .= '<input type="checkbox" id="' . $controlName . '_' . $rowIndex . '" name="' . $controlName . '[]" value="' . $rowIndex . '" ' . $checkedStatus . $cssClassAdd . ' /><label for="' . $controlName . '_' . $rowIndex . '">' . $label . '</label><br/>';

            $rowIndex++;
        }

        if ($addCheckAll) {
            $ret .= '<br/><input type="checkbox" id="' . $controlName . '_ALL" name="' . $controlName . '_ALL" value="" /><label for="' . $controlName . '_ALL"><strong>Check All</strong></label><br/>';
        }

        return $ret;
    }

    public static function GenerateCheckListFromArray(&$rows, $fieldValue, $fieldText, $arrSelectedValues, $controlName, $cssClass = '')
    {
        $ret = '';
        $rowIndex = 1;
        $cssClassAdd = ($cssClass != '') ? ' class="' . $cssClass . '"' : '';
        foreach ($rows as &$row) {
            if ($arrSelectedValues == null) {
                $checkedStatus = '';
            } else {
                $checkedStatus = (in_array($row->{$fieldValue}, $arrSelectedValues)) ? 'checked="checked"' : '';
            }

            $ret .= '<span class="item-wrapper"><input type="checkbox" id="' . $controlName . '_' . $rowIndex . '" name="' . $controlName . '[]" value="' . $row->{$fieldValue} . '" ' . $checkedStatus . $cssClassAdd . ' /><label for="' . $controlName . '_' . $rowIndex . '">' . $row->{$fieldText} . '</label></span>';

            $rowIndex++;
        }

        return $ret;
    }

    public static function JsScript($content, $source)
    {
        if ($source != '') {
            return '<script src="' . $source . '></script>';
        } else {
            return '<script type="text/javascript">' . $content . '</script>';
        }

    }

    public static function JsAlert($message)
    {
        $message = addslashes($message);
        return HtmlControls::JsScript("alert('{$message}');");
    }

    public static function GenerateImage($imgSrc, $alt = '', $attr = '')
    {
        $ret = '<img src="' . $imgSrc . '" alt="' . $alt . '"' . $attr . ' />';
        return $ret;
    }

    public static function GenerateLink($href, $anchor, $attr = '')
    {
        $ret = '<a href="' . $href . '" ' . $attr . ' >' . $anchor . '</a>';
        return $ret;
    }

    public static function GenerateDeleteSelected($deleteName = '')
    {
        $ret = '&nbsp;&nbsp;<a href="javascript:;" onclick="frm.FormDeleteSelected()"><span class="btn btn-default"><i class="fa fa-fw fa-trash-o"></i> ' . $deleteName . '</span></a>';
        return $ret;
    }

    public static function GenerateFormButtons($saveCaption, $clickFunction = 'saveFormData();', $returnUrl = '', $returnLabel = '')
    {
        if ($clickFunction) {
            $clickEvent = 'onclick="' . $clickFunction . '"';
        } else {
            $clickEvent = '';
        }

        $ret = '<a href="javascript:;" ' . $clickEvent . ' class="btn-save"><span class="btn btn-success"><i class="fa fa-fw fa-hand-o-right"></i> ' . $saveCaption . '</span></a>';
        if ($returnUrl != '') {
            $ret .= '<a href="' . $returnUrl . '"><span class="btn btn-default"><i class="fa fa-fw fa-list"></i> ' . $returnLabel . '</span></a>';
        }

        return $ret;
    }

    public static function GenerateRadioList(&$data)
    {
        $ret = '';

        foreach ($data->inputs as &$input) {
            $checkedStatus = ($data->selectedValue == $input->value) ? 'checked="checked"' : '';
            $ret .= '<' . $data->elementTag . '><input type="radio" id="' . $input->id . '" name="' . $data->name . '" value="' . $input->value . '" ' . $checkedStatus . ' ' . $input->attributes . ' /><label for="' . $input->id . '">' . $input->label . '</label></' . $data->elementTag . '>';
        }

        return $ret;
    }

    public static function GenerateMultilanguageTabs(&$languages, &$data, $defaultLanguageId, $contentClass = '')
    {
        $ret = new stdClass();
        $ret->Tabs = '';
        $ret->Content = '';

        $ret->Tabs = '<div role="tabpanel">';
        $ret->Tabs .= '<ul class="nav nav-tabs" role="tablist">';
        foreach ($languages as $lang) {
            $id = 'lang_' . $lang->abbreviation;
            $cssClass = ($lang->id == $defaultLanguageId) ? 'class="active"' : '';
            $ret->Tabs .= '<li role="presentation" ' . $cssClass . '><a href="#' . $id . '" aria-controls="' . $id . '" role="tab" data-toggle="tab">' . $lang->abbreviation . '</a></li>';
        }
        $ret->Tabs .= '</ul>';

        // Tab panes
        $ret->Content .= '<div class="tab-content multilang ' . $contentClass . '">';
        foreach ($languages as $lang) {
            $input = '';
            foreach ($data as &$ctl) {
                $controlId = $ctl->id . '_' . $lang->id;
                $inputValue = $ctl->values[$lang->id];

                $input .= '<div>';
                $input .= '<label for="' . $controlId . '">' . $ctl->label . '</label>';
                $input .= '<span>';
                if ($ctl->type == 'input') {
                    $input .= '<input type="text" class="form-control" name="' . $controlId . '" id="' . $controlId . '" value="' . $inputValue . '" />';
                } else if ($ctl->type == 'textarea') {
                    $input .= '<textarea class="form-control" name="' . $controlId . '" id="' . $controlId . '">' . $inputValue . '</textarea>';
                }

                $input .= '</span>';
                $input .= '</div>';
            }

            $id = 'lang_' . $lang->abbreviation;
            $cssClass = ($lang->id == $defaultLanguageId) ? ' active' : '';
            $ret->Content .= '<div role="tabpanel" class="tab-pane' . $cssClass . '" id="' . $id . '">' . $input . '</div>';
        }
        $ret->Content .= '</div>';

        return $ret;
    }
}

class DropDownListItem
{
    public $value;
    public $text;

    public function __construct($itemText = null, $itemValue = null)
    {
        $this->text = $itemText;
        $this->value = $itemValue;
    }
}
