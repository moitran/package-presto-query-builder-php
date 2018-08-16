<?php

namespace MoiTran\PrestoQueryBuilder;

use MoiTran\PrestoQueryBuilder\Exception\InvalidArgumentException;

/**
 * Trait Where
 * @package MoiTran\PrestoQueryBuilder
 */
trait Where
{
    /**
     * @param $column
     * @param $condition
     * @param $value
     * @param bool $isFirstWhere
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function getWhereAndStr($column, $condition, $value, $isFirstWhere = false)
    {
        $whereType = $isFirstWhere ? 'WHERE' : 'AND';
        return $this->where($column, $condition, $value, $whereType);
    }

    /**
     * @param $column
     * @param $condition
     * @param $value
     * @param bool $isFirstWhere
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function getWhereOrStr($column, $condition, $value, $isFirstWhere = false)
    {
        $whereType = $isFirstWhere ? 'WHERE' : 'OR';
        return $this->where($column, $condition, $value, $whereType);
    }

    /**
     * @param $column
     * @param $condition
     * @param $value
     * @param $whereType
     *
     * @return string
     * @throws InvalidArgumentException
     */
    private function where($column, $condition, $value, $whereType)
    {
        if (!is_string($column) || !is_string($condition)) {
            throw new InvalidArgumentException('$from, $condition and $value argument must be a string');
        }

        if (!(is_string($value) || is_array($value))) {
            throw new InvalidArgumentException('$value argument must be a string or an array');
        }

        $valueStr = is_string($value) ? $value : sprintf('("%s")', implode('","', $value));
        $whereStr = sprintf(' %s %s %s "%s"', $whereType, $column, $condition, $valueStr);

        return $whereStr;
    }
}
