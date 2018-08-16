<?php

namespace MoiTran\PrestoQueryBuilder;

use MoiTran\PrestoQueryBuilder\Exception\InvalidArgumentException;

/**
 * Class Example
 * @package MoiTran\PrestoQueryBuilder
 */
class Query
{
    use Where;

    /**
     * @var bool
     */
    private $isFirstWhere = true;
    /**
     * @var bool
     */
    private $isFirstOrderBy = true;

    const SORT_DESC = 'DESC';
    const SORT_ASC = 'ASC';

    /**
     * @var string
     */
    protected $queryStr = '';

    /**
     * @param mixed $select
     *
     * @return $this
     */
    public function select($select)
    {
        $selection = is_string($select) ? [$select] : $select;
        $this->combineQueryStr("SELECT " . implode(',', $selection));

        return $this;
    }

    /**
     * @param $from
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function from($from, $alias = '')
    {
        if (!is_string($from) || !is_string($alias)) {
            throw new InvalidArgumentException('$from and $as argument must be a string');
        }
        $from = " FROM " . $from;
        if ($alias != '') {
            $alias = ' AS ' . $alias;
        }

        $from .= $alias;

        $this->combineQueryStr($from);

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
    public function whereAnd($column, $condition, $value)
    {
        // call whereAnd function from trait
        $andStr = $this->getWhereAndStr($column, $condition, $value, $this->isFirstWhere);

        if ($this->isFirstWhere) {
            $this->isFirstWhere = false;
        }

        $this->combineQueryStr($andStr);

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
        $orStr = $this->getWhereOrStr($column, $condition, $value);

        if ($this->isFirstWhere) {
            $this->isFirstWhere = false;
        }

        $this->combineQueryStr($orStr);

        return $this;
    }

    /**
     * @param $column
     * @param $values
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function whereIn($column, $values)
    {
        if (!is_string($column)) {
            throw new InvalidArgumentException('$column argument must be a string');
        }

        if (!is_array($values)) {
            throw new InvalidArgumentException('$values argument must be an array');
        }

        $whereInStr = sprintf(' AND %s IN ("%s")', $column, implode('","', $values));
        if ($this->isFirstWhere) {
            $whereInStr = sprintf(' WHERE %s IN ("%s")', $column, implode('","', $values));
        }

        $this->combineQueryStr($whereInStr);

        return $this;
    }

    /**
     * @param WhereGroup $whereGroup
     *
     * @return $this
     */
    public function whereAndGroup(WhereGroup $whereGroup)
    {
        $whereStr = $whereGroup->getWhereConditions();

        if ($this->isFirstWhere) {
            $whereStr = sprintf(' WHERE AND (%s)', $whereStr);
        } else {
            $whereStr = sprintf(' AND (%s)', $whereStr);
        }

        $this->combineQueryStr($whereStr);

        return $this;
    }

    /**
     * @param WhereGroup $whereGroup
     *
     * @return $this
     */
    public function whereOrGroup(WhereGroup $whereGroup)
    {
        $whereStr = $whereGroup->getWhereConditions();

        if ($this->isFirstWhere) {
            $whereStr = sprintf(' WHERE OR (%s)', $whereStr);
        } else {
            $whereStr = sprintf(' OR (%s)', $whereStr);
        }

        $this->combineQueryStr($whereStr);

        return $this;
    }

    /**
     * @param $columns
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function groupBy($columns)
    {
        if (!is_array($columns)) {
            throw new InvalidArgumentException('$columns argument must be an array');
        }

        $groupByStr = ' GROUP BY ' . implode(', ', $columns);

        $this->combineQueryStr($groupByStr);

        return $this;
    }

    /**
     * @param $column
     * @param $sortType
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function orderBy($column, $sortType)
    {
        if (!is_string($column) || !is_string($sortType)) {
            throw new InvalidArgumentException('$column and $sortType argument must be a string');
        }

        $orderByStr = sprintf(', %s %s', $column, $sortType);
        if ($this->isFirstOrderBy) {
            $orderByStr = sprintf(' ORDER BY %s %s', $column, $sortType);
            $this->isFirstOrderBy = false;
        }

        $this->combineQueryStr($orderByStr);

        return $this;
    }

    /**
     * @param $limit
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function limit($limit)
    {
        if (!is_int($limit)) {
            throw new InvalidArgumentException('$limit argument must be an integer');
        }

        $this->combineQueryStr(" LIMIT $limit");

        return $this;
    }

    /**
     * @return string
     */
    public function getQueryStr()
    {
        return $this->queryStr;
    }

    /**
     * @param string $str
     */
    private function combineQueryStr($str)
    {
        $this->queryStr .= $str;
    }
}
