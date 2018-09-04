<?php

namespace MoiTran\PrestoQueryBuilder\Tests;

use MoiTran\PrestoQueryBuilder\WhereGroup;

/**
 * Class WhereGroupTest
 * @package MoiTran\PrestoQueryBuilder\Tests
 */
class WhereGroupTest extends TestCases
{
    /**
     * @throws \MoiTran\PrestoQueryBuilder\Exception\InvalidArgumentException
     */
    public function testWhereAdd()
    {
        $whereGroup = new WhereGroup();
        $actual = $whereGroup->whereAnd('col1', '>', 10)
            ->whereAnd('col2', 'IS', null)
            ->whereAnd('col3', 'IN', [1, 2, 3, 4])
            ->getWhereConditions();

        $expected = "col1 > 10 AND col2 IS NULL AND col3 IN ('1','2','3','4')";
        $this->assertEquals($expected, $actual);
    }

    /**
     * @throws \MoiTran\PrestoQueryBuilder\Exception\InvalidArgumentException
     */
    public function testWhereOr()
    {
        $whereGroup = new WhereGroup();
        $actual = $whereGroup->whereOr('col1', '>', 10)
            ->whereOr('col2', 'IS', null)
            ->whereOr('col3', 'IN', [1, 2, 3, 4])
            ->getWhereConditions();

        $expected = "col1 > 10 OR col2 IS NULL OR col3 IN ('1','2','3','4')";
        $this->assertEquals($expected, $actual);
    }

    /**
     * @throws \MoiTran\PrestoQueryBuilder\Exception\InvalidArgumentException
     */
    public function testWhereOrGroup()
    {
        $whereGroup = new WhereGroup();
        $actual = $whereGroup->whereOrGroup((new WhereGroup())->whereAnd('col1', '!=', 'not1')
            ->whereAnd('col1', '!=', 'not2'))
            ->getWhereConditions();

        $expected = "(col1 != 'not1' AND col1 != 'not2')";
        $this->assertEquals($expected, $actual);
    }

    /**
     * @throws \MoiTran\PrestoQueryBuilder\Exception\InvalidArgumentException
     */
    public function testWhereAndGroup()
    {
        $whereGroup = new WhereGroup();
        $actual = $whereGroup->whereAndGroup((new WhereGroup())->whereAnd('col1', '!=', 'not1')
            ->whereAnd('col1', '!=', 'not2'))
            ->getWhereConditions();

        $expected = "(col1 != 'not1' AND col1 != 'not2')";
        $this->assertEquals($expected, $actual);
    }
}
