<?php

namespace MoiTran\PrestoQueryBuilder\Tests;

use MoiTran\PrestoQueryBuilder\Exception\InvalidArgumentException;
use MoiTran\PrestoQueryBuilder\Query;
use MoiTran\PrestoQueryBuilder\WhereGroup;

/**
 * Class QueryTest
 * @package MoiTran\PrestoQueryBuilder\Tests
 */
class QueryTest extends TestCases
{
    /**
     * @return array
     */
    public function providerSelect()
    {
        return [
            'in-valid' => [
                'select' => new \stdClass(),
                'expected' => '$select argument must be a string or an array',
            ],
            'select-all' => [
                'select' => '*',
                'expected' => 'SELECT *',
            ],
            'select-columns' => [
                'select' => ['query', 'page', 'country', 'device'],
                'expected' => "SELECT query, page, country, device",
            ],
            'select-columns-with-alias' => [
                'select' => [
                    'SUM(clicks)' => 'sumCLicks',
                    'SUM(impressions)' => 'sumImpressions',
                ],
                'expected' => "SELECT SUM(clicks) as sumCLicks, SUM(impressions) as sumImpressions",
            ],
        ];
    }

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
     * @return array
     */
    public function providerFrom()
    {
        return [
            'in-valid-from' => [
                'from' => new \stdClass(),
                'alias' => '',
                'expected' => '$from and $alias argument must be a string',
            ],
            'in-valid-alias' => [
                'from' => 'table1',
                'alias' => new \stdClass(),
                'expected' => '$from and $alias argument must be a string',
            ],
            'no-alias' => [
                'from' => 'table1',
                'alias' => '',
                'expected' => ' FROM (table1)',
            ],
            'with-alias' => [
                'from' => 'SELECT * FROM B',
                'alias' => 'a',
                'expected' => ' FROM (SELECT * FROM B) AS a',
            ],
        ];
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
     * @return array
     */
    public function providerLeftJoin()
    {
        return [
            'invalid-table' => [
                'table' => ['table1'],
                'alias' => '',
                'expected' => '$table and $alias argument must be a string',
            ],
            'invalid-alias' => [
                'table' => 'table1',
                'alias' => ['a'],
                'expected' => '$table and $alias argument must be a string',
            ],
            'no-alias' => [
                'table' => 'table1',
                'alias' => '',
                'expected' => ' LEFT JOIN (table1)',
            ],
            'with-alias' => [
                'table' => 'SELECT * FROM B',
                'alias' => 'b',
                'expected' => ' LEFT JOIN (SELECT * FROM B) AS b',
            ],
        ];
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
     * @return array
     */
    public function providerRightJoin()
    {
        return [
            'invalid-table' => [
                'table' => ['table1'],
                'alias' => '',
                'expected' => '$table and $alias argument must be a string',
            ],
            'invalid-alias' => [
                'table' => 'table1',
                'alias' => ['a'],
                'expected' => '$table and $alias argument must be a string',
            ],
            'no-alias' => [
                'table' => 'table1',
                'alias' => '',
                'expected' => ' RIGHT JOIN (table1)',
            ],
            'with-alias' => [
                'table' => 'SELECT * FROM B',
                'alias' => 'b',
                'expected' => ' RIGHT JOIN (SELECT * FROM B) AS b',
            ],
        ];
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
     * @return array
     */
    public function providerInnerJoin()
    {
        return [
            'invalid-table' => [
                'table' => ['table1'],
                'alias' => '',
                'expected' => '$table and $alias argument must be a string',
            ],
            'invalid-alias' => [
                'table' => 'table1',
                'alias' => ['a'],
                'expected' => '$table and $alias argument must be a string',
            ],
            'no-alias' => [
                'table' => 'table1',
                'alias' => '',
                'expected' => ' INNER JOIN (table1)',
            ],
            'with-alias' => [
                'table' => 'SELECT * FROM B',
                'alias' => 'b',
                'expected' => ' INNER JOIN (SELECT * FROM B) AS b',
            ],
        ];
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
     * @return array
     */
    public function providerFullJoin()
    {
        return [
            'invalid-table' => [
                'table' => ['table1'],
                'alias' => '',
                'expected' => '$table and $alias argument must be a string',
            ],
            'invalid-alias' => [
                'table' => 'table1',
                'alias' => ['a'],
                'expected' => '$table and $alias argument must be a string',
            ],
            'no-alias' => [
                'table' => 'table1',
                'alias' => '',
                'expected' => ' FULL JOIN (table1)',
            ],
            'with-alias' => [
                'table' => 'SELECT * FROM B',
                'alias' => 'b',
                'expected' => ' FULL JOIN (SELECT * FROM B) AS b',
            ],
        ];
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
     * @return array
     */
    public function providerOn()
    {
        return [
            'invalid-leftCol' => [
                'leftCol' => ['table1'],
                'condition' => '=',
                'rightCol' => 'id',
                'expected' => '$leftCol, $condition and $rightCol argument must be a string',
            ],
            'invalid-condition' => [
                'leftCol' => 'id',
                'condition' => ['='],
                'rightCol' => 'id',
                'expected' => '$leftCol, $condition and $rightCol argument must be a string',
            ],
            'invalid-rightCol' => [
                'leftCol' => 'id',
                'condition' => '=',
                'rightCol' => ['id'],
                'expected' => '$leftCol, $condition and $rightCol argument must be a string',
            ],
            'success' => [
                'leftCol' => 'a.id',
                'condition' => '=',
                'rightCol' => 'b.id',
                'expected' => ' ON a.id = b.id',
            ],
        ];
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
        $expected = " WHERE AND (id > 1 AND name = 'test') AND (id > 1 AND name = 'test')";
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
        $expected = " WHERE OR (id > 1 OR name = 'test') OR (id > 1 OR name = 'test')";
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
     * @return array
     */
    public function providerGroupBy()
    {
        return [
            'invalid-columns' => [
                'columns' => new \stdClass(),
                'expected' => '$columns argument must be an array or a string',
            ],
            'string-column' => [
                'columns' => 'id',
                'expected' => ' GROUP BY id',
            ],
            'array-column' => [
                'columns' => ['id', 'name'],
                'expected' => ' GROUP BY id, name',
            ],
        ];
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
     * @return array
     */
    public function providerOrderBy()
    {
        return [
            'invalid-column' => [
                'columns' => new \stdClass(),
                'sortType' => '',
                'expected' => '$column and $sortType argument must be a string',
            ],
            'invalid-sortType' => [
                'columns' => 'id',
                'sortType' => [''],
                'expected' => '$column and $sortType argument must be a string',
            ],
            'success' => [
                'columns' => 'id',
                'sortType' => 'DESC',
                'expected' => ' ORDER BY id DESC, id DESC',
            ],
        ];
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
     * @return array
     */
    public function providerLimit()
    {
        return [
            'invalid-limit-object' => [
                'limit' => new \stdClass(),
                'expected' => '$limit argument must be an integer',
            ],
            'invalid-limit-string' => [
                'limit' => 'limit',
                'expected' => '$limit argument must be an integer',
            ],
            'invalid-limit-array' => [
                'limit' => [1],
                'expected' => '$limit argument must be an integer',
            ],
            'success' => [
                'limit' => 10,
                'expected' => ' LIMIT 10',
            ],
        ];
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
}
