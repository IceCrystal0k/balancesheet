<?php
namespace App\Helpers;

use App\Helpers\HtmlControls;
use App\Models\BalanceType;
use App\Models\Currency;
use App\Models\Role;
use App\Models\Target;

class SelectUtils
{
    /**
     * get currency for dropdown, in html format, for the given $selectedValue
     * @param {number} $selectedValue selected currency
     * @return {string} dropdown data, in html format
     */
    public static function getCurrencySelectOptions($selectedValue)
    {
        $currencyList = Currency::select('iso_code', 'name', 'flag')->where('status', 1)->orderBy('name')->get();
        $selectOptions = HtmlControls::GenerateDropDownList($currencyList, ['value' => $selectedValue, 'valueField' => 'iso_code', 'textField' => 'name',
            'attribute' => ['expression' => 'data-kt-flag="' . asset('media/theme/flags/') . '/%s"', 'fields' => ['flag']]]);

        return $selectOptions;
    }

    /**
     * get balance type for dropdown, in html format, for the given $selectedValue
     * @param {number} $selectedValue selected currency
     * @return {string} dropdown data, in html format
     */
    public static function getBalanceTypeSelectOptions($selectedValue)
    {
        $selectedValue = StringUtils::getIntegerValue($selectedValue);
        $list = BalanceType::select('id', 'name')->orderBy('name')->get();
        $selectOptions = HtmlControls::GenerateDropDownList($list, ['value' => $selectedValue, 'valueField' => 'id', 'textField' => 'name']);

        return $selectOptions;
    }

    /**
     * get target for dropdown, in html format, for the given $selectedValue
     * @param {number} $selectedValue selected currency
     * @return {string} dropdown data, in html format
     */
    public static function getTargetSelectOptions($selectedValue)
    {
        $selectedValue = StringUtils::getIntegerValue($selectedValue);
        $list = Target::select('id', 'name')->orderBy('name')->get();
        $selectOptions = HtmlControls::GenerateDropDownList($list, ['value' => $selectedValue, 'valueField' => 'id', 'textField' => 'name']);

        return $selectOptions;
    }

    /**
     * get date format for dropdown, in html format, for the given $selectedValue
     * @param {number} $selectedValue selected product id
     * @return {string} dropdown data, in html format
     */
    public static function getDateFormatSelectOptions($selectedValue)
    {
        $selectedValue = StringUtils::getIntegerValue($selectedValue);
        $data = HtmlControls::ArrayToSelectOptions(config('settings.date_format'), '');
        return HtmlControls::GenerateDropDownList($data, ['value' => $selectedValue, 'valueField' => 'value', 'textField' => 'label']);
    }

    /**
     * get date format separator for dropdown, in html format, for the given $selectedValue
     * @param {number} $selectedValue selected product id
     * @return {string} dropdown data, in html format
     */
    public static function getDateFormatSeparatorSelectOptions($selectedValue)
    {
        $selectedValue = StringUtils::getIntegerValue($selectedValue);
        $data = HtmlControls::ArrayToSelectOptions(config('settings.date_format_separator'), '');
        return HtmlControls::GenerateDropDownList($data, ['value' => $selectedValue, 'valueField' => 'value', 'textField' => 'label']);
    }

    /**
     * get role for dropdown, in html format, for the given $selectedValue
     * @param {number} $selectedValue selected iso_code
     * @return {string} dropdown data, in html format
     */
    public static function getRoleSelectOptions($selectedValue)
    {
        $selectedValue = StringUtils::getIntegerValue($selectedValue);
        $roleList = Role::select('name', 'id')->orderBy('name')->get();
        return HtmlControls::GenerateDropDownList($roleList, ['value' => $selectedValue, 'valueField' => 'id', 'textField' => 'name']);
    }
}
