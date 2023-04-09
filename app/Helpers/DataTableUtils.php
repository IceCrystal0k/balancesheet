<?php
namespace App\Helpers;

class DataTableUtils
{
    /**
     * get the sort info from datatable request and
     * @param {object} $request http request from datatables
     * @return {object} { field, dir } or null if not found
     */
    public static function getRequestSort($request)
    {
        $orderInfo = $request->has('order') ? $request->order : null;
        $response = null;
        if ($orderInfo && count($orderInfo) > 0) {
            $orderData = $orderInfo[0];
            $orderColumn = $request->columns[$orderData['column']];
            $columnName = $orderColumn['name'];
            $response = (object) ['field' => $columnName, 'dir' => $orderData['dir']];
        }
        return $response;
    }

    public static function applyRequestSort($request, $query, $ignoreFields = null)
    {
        $orderInfo = self::getRequestSort($request);
        if ($orderInfo) {
            if (!$ignoreFields || ($ignoreFields && !array_search($orderInfo->field, $ignoreFields))) {
                $query->orderBy($orderInfo->field, $orderInfo->dir);
            }
        }
    }
}
