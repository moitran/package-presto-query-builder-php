<?php

namespace MoiTran\PrestoQueryBuilder\Tests\Provider;

/**
 * Trait WhereTestProvider
 * @package MoiTran\PrestoQueryBuilder\Tests\Provider
 */
trait WhereTestProvider
{
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
                'value' => null,
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
                'expected' => " AND col IN ('1', '2', '3')",
            ],
        ];
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
                'value' => null,
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
                'expected' => " OR col IN ('1', '2', '3')",
            ],
        ];
    }
}
