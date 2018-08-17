<?php

namespace MoiTran\PrestoQueryBuilder;

use MoiTran\PrestoQueryBuilder\Exception\InvalidArgumentException;

/**
 * Class Example
 * @package MoiTran\PrestoQueryBuilder
 */
class Query extends Base
{
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

    const LEFT_JOIN = 'LEFT';
    const RIGHT_JOIN = 'RIGHT';
    const INNER_JOIN = 'INNER';
    const FULL_JOIN = 'FULL';

    /**
     * @var string
     */
    protected $queryStr = '';

    /**
     * @param $select
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function select($select)
    {
        if (!(is_string($select) || is_array($select))) {
            throw new InvalidArgumentException('$select argument must be a string or an array');
        }

        $selection = $select;
        if (is_array($select)) {
            $selection = implode(', ', array_map(
                function ($value, $key) {
                    if (is_numeric($key)) {
                        return $value;
                    }

                    return sprintf("%s as %s", $key, $value);
                },
                $select,
                array_keys($select)
            ));
        }

        $this->combineQueryStr("SELECT " . $selection);

        return $this;
    }

    /**
     * @param $from
     * @param string $alias
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function from($from, $alias = '')
    {
        if (!is_string($from) || !is_string($alias)) {
            throw new InvalidArgumentException('$from and $alias argument must be a string');
        }
        $from = sprintf(" FROM (%s)", $from);
        if ($alias != '') {
            $alias = ' AS ' . $alias;
        }

        $from .= $alias;

        $this->combineQueryStr($from);

        return $this;
    }

    /**
     * @param $table
     * @param string $alias
     *
     * @return Query
     * @throws InvalidArgumentException
     */
    public function leftJoin($table, $alias = '')
    {
        return $this->join($table, $alias, self::LEFT_JOIN);
    }

    /**
     * @param $table
     * @param string $alias
     *
     * @return Query
     * @throws InvalidArgumentException
     */
    public function rightJoin($table, $alias = '')
    {
        return $this->join($table, $alias, self::RIGHT_JOIN);
    }

    /**
     * @param $table
     * @param string $alias
     *
     * @return Query
     * @throws InvalidArgumentException
     */
    public function innerJoin($table, $alias = '')
    {
        return $this->join($table, $alias, self::INNER_JOIN);
    }

    /**
     * @param $table
     * @param string $alias
     *
     * @return Query
     * @throws InvalidArgumentException
     */
    public function fullJoin($table,$alias = '')
    {
        return $this->join($table, self::FULL_JOIN, $alias);
    }

    /**
     * @param $leftCol
     * @param $condition
     * @param $rightCol
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function on($leftCol, $condition, $rightCol)
    {
        if (!is_string($leftCol) || !is_string($condition) || !is_string($rightCol)) {
            throw new InvalidArgumentException('$leftCol, $condition and $rightCol argument must be a string');
        }

        $joinStr = sprintf(' ON %s %s %s', $leftCol, $condition, $rightCol);
        $this->combineQueryStr($joinStr);

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
        return $this->where($column, $condition, $this->removeSpecialChars($value), 'AND');
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
        return $this->where($column, $condition, $this->removeSpecialChars($value), 'OR');
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
        if (!(is_array($columns) || is_string($columns))) {
            throw new InvalidArgumentException('$columns argument must be an array or as string');
        }

        $groupValue = is_string($columns) ? $columns : implode(', ', $columns);
        $groupByStr = ' GROUP BY ' . $groupValue;

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
     * @param $column
     * @param $condition
     * @param $value
     * @param $whereType
     *
     * @return $this|string
     * @throws InvalidArgumentException
     */
    private function where($column, $condition, $value, $whereType)
    {
        if ($whereType == 'AND') {
            // call whereAnd function from trait
            $whereStr = $this->getWhereAndStr(
                $column,
                $condition,
                $value,
                $this->isFirstWhere
            );
        } else {
            // call whereOr function from trait
            $whereStr = $this->getWhereOrStr(
                $column,
                $condition,
                $value,
                $this->isFirstWhere
            );
        }


        if ($this->isFirstWhere) {
            $this->isFirstWhere = false;
        }

        $this->combineQueryStr($whereStr);

        return $this;
    }

    /**
     * @param $table
     * @param $alias
     * @param $joinType
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    private function join($table, $alias, $joinType)
    {
        if (!is_string($table) || !is_string($alias)) {
            throw new InvalidArgumentException('$table and $alias argument must be a string');
        }

        if ($alias != '') {
            $alias = ' AS ' . $alias;
        }
        $joinStr = sprintf(' %s JOIN (%s) %s', $joinType, $table, $alias);

        $this->combineQueryStr($joinStr);

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
