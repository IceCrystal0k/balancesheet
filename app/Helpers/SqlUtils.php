<?php
namespace App\Helpers;

class SqlUtils
{
    /**
     * bulk update table
     * example: UpdateRowsBulk('table_name', array(id, name, description), array( array(1, 't1', 't1 describe'), array(2, 't2', 't2 describe')), array('t.id'=>'v.id'))
     * @param {string} $table name of the table that will be updated
     * @param {array} $fields table columns to be updated, must include the primary key(s)
     * @param {array} $rows array with rows that contains the values of the columns, in the order specified by the $fields
     * @param {array} $where must be an array with the primary key(s) that are joined. The tables aliases are 't' and 'v'
     * @param {number} $bulkSize how many rows to update per batch
     * @return {array} list of object { sql, params}
     */
    //
    // example: UpdateRowsBulk('table_name', array(id, name, description), array( array(1, 't1', 't1 desc'), array(2, 't2', 't2 desc')), array('t.id'=>'v.id'))
    public function UpdateRowsBulk($table, $fields, $rows, $where, $bulkSize = 50)
    {
        $queryList = $this->BuildUpdateBulkQuery($table, $fields, $rows, $where, $bulkSize);
        if (count($queryList) > 0) {
            foreach ($queryList as $query) {
                \DB::update($query->sql, $query->params);
            }
        }
    }

    /**
     * update rows bulk for a single field
     * @param {string} $table name of the table that will be updated
     * @param {array} $rows array with rows that contains the values of the columns, in the order specified by the $fields
     * @param {string} $idField name of the primary key field
     * @param {string} $updateField name of the field that will be updated
     */
    public function UpdateRowsBulkSingleField($table, $rows, $idField, $updateField)
    {
        $caseList = [];
        $idList = [];
        $paramNames = [];
        $paramValues = [];
        foreach ($rows as $item) {
            $idVal = $item->{$idField};
            array_push($caseList, "WHEN '{$idVal}' then ?");
            array_push($idList, $idVal);
            array_push($params, $item->{$updateField});
        }

        $ids = implode(',', $idList);
        $cases = implode(' ', $caseList);

        if (!empty($ids)) {
            \DB::update("UPDATE {$table} SET `{$updateField}` = CASE `{$idField}` {$cases} END WHERE `{$idField}` in ({$ids})", $params);
        }
    }

    /**
     *   Builds an update sql with the following syntax:
     *     UPDATE my_table t
     *       JOIN (
     * SELECT 1 as id, 10 as _col1, 20 as _col2
     * UNION ALL
     * SELECT 2, 5, 10
     * UNION ALL
     * SELECT 3, 15, 30
     * ) v ON t.id = v.id
     * SET t._col1 = v._col1, t._col2 = v._col2;

     */
    private function BuildUpdateBulkQuery($table, $fields, $rows, $where, $bulkSize = 50)
    {
        if ($rows == null) {
            return false;
        }

        $queryList = array();

        $sqlUpdate = 'UPDATE ' . $this->EncodeNameForDb($table) . ' t JOIN (';
        $sqlFields = $this->GetSqlFieldsFromArray($fields);

        $sqlValues = '';
        $rowIndex = 0;
        $addHeaderFields = true; // flag to indicate when to add aliases
        $rowsCount = count($rows);
        $params = [];

        foreach ($rows as &$row) {
            $sqlValues .= ' SELECT ';
            $fieldIndex = 0;
            foreach ($row as &$val) {
                $fieldName = $fields[$fieldIndex];
                $alias = $addHeaderFields ? " as `{$fieldName}`" : '';

                $sqlVal = $this->GetSqlValue($val);
                if ($sqlVal === '?') {
                    array_push($params, $val);
                }
                $sqlValues .= " {$sqlVal}{$alias}, ";

                $fieldIndex++;
            }

            $addHeaderFields = false;
            $sqlValues = substr($sqlValues, 0, strlen($sqlValues) - 2);

            if (($rowIndex > 0 && ($rowIndex % $bulkSize) == 0) || $rowIndex == $rowsCount - 1) {
                $sqlValues .= ') v ON ' . $this->GetConditionForBulkUpdate($where) . ' SET ';
                foreach ($fields as &$field) {
                    $sqlValues .= "t.{$field} = v.{$field},";
                }

                $sqlValues = substr($sqlValues, 0, strlen($sqlValues) - 1);
                $sqlValues .= ';';
                array_push($queryList, (object) ['sql' => $sqlUpdate . " {$sqlValues}", 'params' => $params]);
                $sqlValues = '';
                $params = [];
                $addHeaderFields = true;
            } else {
                $sqlValues .= ' UNION ALL ';
            }

            $rowIndex++;
        }

        return $queryList;
    }

    private function GetConditionForBulkUpdate($where)
    {
        $ret = '';
        foreach ($where as $key => $val) {
            $ret .= " {$key} = {$val} AND ";
        }
        $ret = substr($ret, 0, strlen($ret) - 5);
        return $ret;
    }

    private function EncodeNameForDb($str)
    {
        if (strpos($str, '.') > 0) {
            $arr = explode('.', $str);
            return '`' . implode('`.`', $arr) . '`';
        } else {
            return '`' . $str . '`';
        }

    }

    private function GetSqlFieldsFromArray(&$fields)
    {
        $sqlFields = '';
        foreach ($fields as $key) {
            $sqlFields .= " `{$key}`, ";
        }
        $sqlFields = substr($sqlFields, 0, strlen($sqlFields) - 2);
        return $sqlFields;
    }

    private function GetSqlValue($val)
    {
        // for columns or keywords, use brackets; i.e.  $field=>'[field + 1]'
        if (strlen($val) > 0 && $val[0] == '[' && $val[strlen($val) - 1] == ']') {
            $val = substr($val, 1, strlen($val) - 2);
        } else {
            // $val = "'{$val}'";
            $val = '?';
        }

        return $val;
    }
}