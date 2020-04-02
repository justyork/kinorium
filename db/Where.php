<?php
/**
 * Author: yorks
 * Date: 02.04.2020
 */

namespace App;


/**
 * Class Where
 * @package App
 */
abstract class Where
{

    /** Generate inset query string from data
     * @param array $values
     * @return string
     */
    protected function prepareInsertQuery(array $values): string
    {
        $keys = [];
        $vals = [];
        foreach ($values as $key => $value) {
            $val = htmlspecialchars($value);
            $keys[] = $key;
            $vals[] = $val;
        }
        $key_string = implode(',', $keys);
        $value_string = implode(',', $vals);
        return "({$key_string}) VALUES ($value_string)";
    }

    /** Generate update query string from data
     * @param array $values
     * @return string
     */
    protected function prepareUpdateQuery(array $values): string
    {
        $return = [];
        foreach ($values as $key => $value) {
            $val = htmlspecialchars($value);
            $return[] = "`$key` = '$val'";
        }

        return implode(',', $return);
    }

    /** Execute where condition it can be int, string or array
     * @param $where
     * @return mixed
     * @throws \Exception
     */
    protected function executeWhere($where)
    {
        if (is_int($where)) {
            $where = "id = {$where}";
        } elseif (is_array($where)) {
            $where = $this->prepareWhereQueryArray($where);
        } elseif (!is_string($where)) {
            throw new \Exception("Incorrect where clause");
        }

        return $where;
    }

    /** Execute where condition from array
     * @param array $where
     * @return mixed
     */
    protected function prepareWhereQueryArray(array $where)
    {
        $return = [];
        $i = 0;
        foreach ($where as $key => $value) {
            if (is_array($value)) {
                $condition = $value[1] ?? '=';
                $or_and = $i > 0 ? ($value[2] ?? 'AND') : '';
                $return[] = "{$or_and} {$key} {$condition} {$value[0]}";
            } else {
                $return[] = "{$key} = {$value}";
            }
            $i++;
        }

        return implode(',', $return);
    }
}
