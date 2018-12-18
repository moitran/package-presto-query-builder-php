<?php

namespace MoiTran\PrestoQueryBuilder\Tests;

use MoiTran\PrestoQueryBuilder\Exception\InvalidArgumentException;
use MoiTran\PrestoQueryBuilder\Query;
use MoiTran\PrestoQueryBuilder\WhereGroup;
use MoiTran\PrestoQueryBuilder\Tests\Provider\QueryTestProvider;

/**
 * Class QueryTest
 * @package MoiTran\PrestoQueryBuilder\Tests
 */
class QueryTest extends TestCases
{
    use QueryTestProvider;

    /**
     * @param $select
     * @param $expected
     *
     * @dataProvider providerSelect
     */
    public function testSelect($select, $expected)
    {
        $query = new Query();
        try {
            $actual = $query->select($select)->getQueryStr();
            $this->assertEquals($expected, $actual);
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
            $this->assertEquals($expected, $e->getMessage());
        }
    }

    /**
     * @param $from
     * @param @alias
     * @param $expected
     *
     * @dataProvider providerFrom
     */
    public function testFrom($from, $alias, $expected)
    {
        $query = new Query();
        try {
            $actual = $query->from($from, $alias)->getQueryStr();
            $this->assertEquals($expected, $actual);
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
            $this->assertEquals($expected, $e->getMessage());
        }
    }

    /**
     * @param $table
     * @param $alias
     * @param $expected
     *
     * @dataProvider providerLeftJoin
     */
    public function testLeftJoin($table, $alias, $expected)
    {
        $query = new Query();
        try {
            $actual = $query->leftJoin($table, $alias)->getQueryStr();
            $this->assertEquals($expected, $actual);
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
            $this->assertEquals($expected, $e->getMessage());
        }
    }

    /**
     * @param $table
     * @param $alias
     * @param $expected
     *
     * @dataProvider providerRightJoin
     */
    public function testRightJoin($table, $alias, $expected)
    {
        $query = new Query();
        try {
            $actual = $query->rightJoin($table, $alias)->getQueryStr();
            $this->assertEquals($expected, $actual);
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
            $this->assertEquals($expected, $e->getMessage());
        }
    }

    /**
     * @param $table
     * @param $alias
     * @param $expected
     *
     * @dataProvider providerInnerJoin
     */
    public function testInnerJoin($table, $alias, $expected)
    {
        $query = new Query();
        try {
            $actual = $query->innerJoin($table, $alias)->getQueryStr();
            $this->assertEquals($expected, $actual);
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
            $this->assertEquals($expected, $e->getMessage());
        }
    }

    /**
     * @param $table
     * @param $alias
     * @param $expected
     *
     * @dataProvider providerFullJoin
     */
    public function testFullJoin($table, $alias, $expected)
    {
        $query = new Query();
        try {
            $actual = $query->fullJoin($table, $alias)->getQueryStr();
            $this->assertEquals($expected, $actual);
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
            $this->assertEquals($expected, $e->getMessage());
        }
    }

    /**
     * @param $leftCol
     * @param $condition
     * @param $rightCol
     * @param $expected
     *
     * @dataProvider providerOn
     */
    public function testOn($leftCol, $condition, $rightCol, $expected)
    {
        $query = new Query();
        try {
            $actual = $query->on($leftCol, $condition, $rightCol)->getQueryStr();
            $this->assertEquals($expected, $actual);
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
            $this->assertEquals($expected, $e->getMessage());
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testWhereAnd()
    {
        $query = new Query();
        $actual = $query->whereAnd('id', '=', 1)->whereAnd('name', '!=', 'test')->getQueryStr();
        $this->assertEquals(" WHERE id = 1 AND name != 'test'", $actual);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testWhereOr()
    {
        $query = new Query();
        $actual = $query->whereOr('id', '=', 1)->whereOr('name', '!=', 'test')->getQueryStr();
        $this->assertEquals(" WHERE id = 1 OR name != 'test'", $actual);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testWhereAndGroup()
    {
        $whereGroup = new WhereGroup();
        $whereGroup->whereAnd('id', '>', 1)
            ->whereAnd('name', '=', 'test');
        $query = new Query();
        $actual = $query->whereAndGroup($whereGroup)->whereAndGroup($whereGroup)->getQueryStr();
        $expected = " WHERE (id > 1 AND name = 'test') AND (id > 1 AND name = 'test')";
        $this->assertEquals($expected, $actual);

        $whereGroup = new WhereGroup();
        $query = new Query();
        $actual = $query->select('*')->from('db')->whereAndGroup($whereGroup)->getQueryStr();
        $expected = 'SELECT * FROM (db)';
        $this->assertEquals($expected, $actual);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testWhereOrGroup()
    {
        $whereGroup = new WhereGroup();
        $whereGroup->whereAnd('id', '>', 1)
            ->whereOr('name', '=', 'test');
        $query = new Query();
        $actual = $query->whereOrGroup($whereGroup)->whereOrGroup($whereGroup)->getQueryStr();
        $expected = " WHERE (id > 1 OR name = 'test') OR (id > 1 OR name = 'test')";
        $this->assertEquals($expected, $actual);

        $whereGroup = new WhereGroup();
        $query = new Query();
        $actual = $query->select('*')->from('db')->whereOrGroup($whereGroup)->getQueryStr();
        $expected = 'SELECT * FROM (db)';
        $this->assertEquals($expected, $actual);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testUnionAll()
    {
        $query1 = new Query();
        $query1->select(['id as col1', 'null as col2', 'null as col3'])->from('table1')->whereAnd('id', '=', 1);

        $query2 = new Query();
        $query2->select(['null as col1', 'id as col2', 'null as col3'])->from('table2')->whereAnd('id', '=', 2);

        $query3 = new Query();
        $query3->select(['null as col1', 'null as col2', 'id as col3'])->from('table3')->whereAnd('id', '=', 3);

        $actual = $query1->unionAll($query2)->unionAll($query3)->getQueryStr();
        $expected = "(SELECT id as col1, null as col2, null as col3 FROM (table1) WHERE id = 1) " .
            "UNION ALL (SELECT null as col1, id as col2, null as col3 FROM (table2) WHERE id = 2) " .
            "UNION ALL (SELECT null as col1, null as col2, id as col3 FROM (table3) WHERE id = 3)";
        $this->assertEquals($expected, $actual);
    }

    /**
     * @param $columns
     * @param $expected
     *
     * @dataProvider providerGroupBy
     */
    public function testGroupBy($columns, $expected)
    {
        $query = new Query();
        try {
            $actual = $query->groupBy($columns)->getQueryStr();
            $this->assertEquals($expected, $actual);
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
            $this->assertEquals($expected, $e->getMessage());
        }
    }

    /**
     * @param $column
     * @param $sortType
     * @param $expected
     *
     * @dataProvider providerOrderBy
     */
    public function testOrderBy($column, $sortType, $expected)
    {
        $query = new Query();
        try {
            $actual = $query->orderBy($column, $sortType)->orderBy($column, $sortType)->getQueryStr();
            $this->assertEquals($expected, $actual);
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
            $this->assertEquals($expected, $e->getMessage());
        }
    }

    /**
     * @param $limit
     * @param $expected
     *
     * @dataProvider providerLimit
     */
    public function testLimit($limit, $expected)
    {
        $query = new Query();
        try {
            $actual = $query->limit($limit)->getQueryStr();
            $this->assertEquals($expected, $actual);
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
            $this->assertEquals($expected, $e->getMessage());
        }
    }

    /**
     * @param $column
     * @param $condition
     * @param $value
     * @param $firstHaving
     * @param $expected
     *
     * @dataProvider providerAndHaving
     */
    public function testAndHaving($column, $condition, $value, $firstHaving, $expected)
    {
        $query = new Query();
        try {
            $this->setPrivateProp($query, 'isFirstHaving', $firstHaving);
            $actual = $query->andHaving($column, $condition, $value)->getQueryStr();
            $this->assertEquals($expected, $actual);
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
            $this->assertEquals($expected, $e->getMessage());
        }
    }

    /**
     * @param $column
     * @param $condition
     * @param $value
     * @param $firstHaving
     * @param $expected
     *
     * @dataProvider providerOrHaving
     */
    public function testOrHaving($column, $condition, $value, $firstHaving, $expected)
    {
        $query = new Query();
        try {
            $this->setPrivateProp($query, 'isFirstHaving', $firstHaving);
            $actual = $query->orHaving($column, $condition, $value)->getQueryStr();
            $this->assertEquals($expected, $actual);
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
            $this->assertEquals($expected, $e->getMessage());
        }
    }
}
