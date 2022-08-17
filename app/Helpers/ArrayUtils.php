<?php
namespace App\Helpers;

class ArrayUtils
{
    /**
     * sanitize a string of integers which are comma separated: '1,3,5,7'
     */
    public static function sanitizeIntString($data, $addZeroValues = false)
    {
        $resultList = [];
        $dataList = explode(',', $data);
        foreach ($dataList as $val) {
            $intVal = (int) trim($val);
            if ($intVal !== 0 || ($intVal === 0 && $addZeroValues)) {
                array_push($resultList, $intVal);
            }
        }

        return $resultList;
    }

    public static function findObjectById($arr, $id)
    {
        foreach ($arr as $element) {
            if ($id === $element->id) {
                return $element;
            }
        }
        return false;
    }
}
