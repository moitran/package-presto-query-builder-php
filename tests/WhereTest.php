<?php

namespace MoiTran\PrestoQueryBuilder\Tests;

use MoiTran\PrestoQueryBuilder\Exception\InvalidArgumentException;
use MoiTran\PrestoQueryBuilder\Where;

/**
 * Class ExampleTest
 */
class WhereTest extends TestCases
{
    use Where;

    /**
     * @return array
     */
    public function providerWhereThrowException()
    {
        return [
            'column-invalid' => [
                'column' => ['err'],
                'condition' => '=',
                'value' => 1,
                'expected' => '$column and $condition argument must be a string',
            ],
            'condition-invalid' => [
                'column' => 'a',
                'condition' => ['test'],
                'value' => 1,
                'expected' => '$column and $condition argument must be a string',
            ],
            'value-invalid' => [
                'column' => 'a',
                'condition' => '=',
                'value' => new \stdClass(),
                'expected' => '$value argument must be a string, a numeric or an array',
            ],
        ];
    }

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
     * @return array
     */
    public function providerGetWhereAndStr()
    {
        return [
            'is-first-where' => [
                'column' => 'col',
                'condition' => '=',
                'value' => 1,
                'isFirstWhere' => true,
                'expected' => ' WHERE col = 1',
            ],
            'null-value' => [
                'column' => 'col',
                'condition' => 'IS',
                'value' => NULL,
                'isFirstWhere' => false,
                'expected' => " AND col IS NULL",
            ],
            'numeric-value' => [
                'column' => 'col',
                'condition' => '>',
                'value' => 1.5,
                'isFirstWhere' => false,
                'expected' => " AND col > 1.5",
            ],
            'string-value' => [
                'column' => 'col',
                'condition' => '=',
                'value' => 'test',
                'isFirstWhere' => false,
                'expected' => " AND col = 'test'",
            ],
            'array-value' => [
                'column' => 'col',
                'condition' => 'IN',
                'value' => [1, 2, 3],
                'isFirstWhere' => false,
                'expected' => " AND col IN ('1','2','3')",
            ],
        ];
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
     * @return array
     */
    public function providerGetWhereOrStr()
    {
        return [
            'is-first-where' => [
                'column' => 'col',
                'condition' => '=',
                'value' => 1,
                'isFirstWhere' => true,
                'expected' => ' WHERE col = 1',
            ],
            'null-value' => [
                'column' => 'col',
                'condition' => 'IS',
                'value' => NULL,
                'isFirstWhere' => false,
                'expected' => " OR col IS NULL",
            ],
            'numeric-value' => [
                'column' => 'col',
                'condition' => '>',
                'value' => 1.5,
                'isFirstWhere' => false,
                'expected' => " OR col > 1.5",
            ],
            'string-value' => [
                'column' => 'col',
                'condition' => '=',
                'value' => 'test',
                'isFirstWhere' => false,
                'expected' => " OR col = 'test'",
            ],
            'array-value' => [
                'column' => 'col',
                'condition' => 'IN',
                'value' => [1, 2, 3],
                'isFirstWhere' => false,
                'expected' => " OR col IN ('1','2','3')",
            ],
        ];
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
