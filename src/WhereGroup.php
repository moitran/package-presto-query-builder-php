<?php

namespace MoiTran\PrestoQueryBuilder;

use MoiTran\PrestoQueryBuilder\Exception\InvalidArgumentException;

/**
 * Class WhereAndGroup
 * @package MoiTran\PrestoQueryBuilder
 */
class WhereGroup extends Base
{
    /**
     * @var bool
     */
    private $isFirstCondition = true;

    /**
     * @var string
     */
    private $whereStr = '';

    /**
     * @param $column
     * @param $condition
     * @param $value
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function whereAnd($column, $condition, $value)
    {
        // call whereAnd function from trait
        $andStr = $this->getWhereAndStr($column, $condition, $this->removeSpecialChars($value));

        if ($this->isFirstCondition) {
            $andStr = substr($andStr, 5);
            $this->isFirstCondition = false;
        }

        $this->combineWhereStr($andStr);

        return $this;
    }

    /**
     * @param $column
     * @param $condition
     * @param $value
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function whereOr($column, $condition, $value)
    {
        // call whereAnd function from trait
        $orStr = $this->getWhereOrStr($column, $condition, $this->removeSpecialChars($value));

        if ($this->isFirstCondition) {
            $orStr = substr($orStr, 5);
            $this->isFirstCondition = false;
        }

        $this->combineWhereStr($orStr);

        return $this;
    }

    /**
     * @param string $str
     */
    private function combineWhereStr($str)
    {
        $this->whereStr .= $str;
    }

    /**
     * @return string
     */
    public function getWhereConditions()
    {
        return $this->whereStr;
    }
}
