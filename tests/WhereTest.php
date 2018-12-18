<?php

namespace MoiTran\PrestoQueryBuilder\Tests;

use MoiTran\PrestoQueryBuilder\Exception\InvalidArgumentException;
use MoiTran\PrestoQueryBuilder\Where;
use MoiTran\PrestoQueryBuilder\Tests\Provider\WhereTestProvider;

/**
 * Class ExampleTest
 */
class WhereTest extends TestCases
{
    use Where;
    use WhereTestProvider;

    /**
     * @param $column
     * @param $condition
     * @param $value
     * @param $expected
     *
     * @dataProvider providerWhereThrowException
     */
    public function testWhereThrowException($column, $condition, $value, $expected)
    {
        try {
            $this->getWhereAndStr($column, $condition, $value);
        } catch (\Exception $e) {
            $this->assertInstanceOf(InvalidArgumentException::class, $e);
            $this->assertEquals($expected, $e->getMessage());
        }
    }

    /**
     * @param $column
     * @param $condition
     * @param $value
     * @param $isFirstWhere
     * @param $expected
     *
     * @dataProvider providerGetWhereAndStr
     * @throws InvalidArgumentException
     */
    public function testGetWhereAndStr($column, $condition, $value, $isFirstWhere, $expected)
    {
        $actual = $this->getWhereAndStr($column, $condition, $value, $isFirstWhere);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @param $column
     * @param $condition
     * @param $value
     * @param $isFirstWhere
     * @param $expected
     *
     * @dataProvider providerGetWhereOrStr
     * @throws InvalidArgumentException
     */
    public function testGetWhereOrStr($column, $condition, $value, $isFirstWhere, $expected)
    {
        $actual = $this->getWhereOrStr($column, $condition, $value, $isFirstWhere);
        $this->assertEquals($expected, $actual);
    }
}
