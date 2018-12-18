<?php

namespace MoiTran\PrestoQueryBuilder\Tests\Provider;

/**
 * Trait QueryTestProvider
 * @package MoiTran\PrestoQueryBuilder\Tests\Provider
 */
trait QueryTestProvider
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
     * @return array
     */
    public function providerAndHaving()
    {
        return [
            'column-type-invalid' => [
                'column' => ['col1'],
                'condition' => '>',
                'value' => 1,
                'firstHaving' => true,
                'expected' => '$column and $condition argument must be a string',
            ],
            'condition-type-invalid' => [
                'column' => 'col1',
                'condition' => ['>'],
                'value' => 1,
                'firstHaving' => true,
                'expected' => '$column and $condition argument must be a string',
            ],
            'value-type-invalid' => [
                'column' => 'col1',
                'condition' => '>',
                'value' => new \stdClass(),
                'firstHaving' => true,
                'expected' => '$value argument must be a string, a numeric or an array',
            ],
            'null-value' => [
                'column' => 'col1',
                'condition' => '=',
                'value' => NULL,
                'firstHaving' => true,
                'expected' => ' HAVING col1 = NULL',
            ],
            'int-value' => [
                'column' => 'col1',
                'condition' => '>',
                'value' => 10,
                'firstHaving' => true,
                'expected' => ' HAVING col1 > 10',
            ],
            'string-value' => [
                'column' => 'col1',
                'condition' => '!=',
                'value' => 'str',
                'firstHaving' => true,
                'expected' => " HAVING col1 != 'str'",
            ],
            'array-value' => [
                'column' => 'col1',
                'condition' => 'IN',
                'value' => [1, 2, 3],
                'firstHaving' => false,
                'expected' => " AND col1 IN ('1', '2', '3')",
            ],
        ];
    }

    /**
     * @return array
     */
    public function providerOrHaving()
    {
        return [
            'column-type-invalid' => [
                'column' => ['col1'],
                'condition' => '>',
                'value' => 1,
                'firstHaving' => true,
                'expected' => '$column and $condition argument must be a string',
            ],
            'condition-type-invalid' => [
                'column' => 'col1',
                'condition' => ['>'],
                'value' => 1,
                'firstHaving' => true,
                'expected' => '$column and $condition argument must be a string',
            ],
            'value-type-invalid' => [
                'column' => 'col1',
                'condition' => '>',
                'value' => new \stdClass(),
                'firstHaving' => true,
                'expected' => '$value argument must be a string, a numeric or an array',
            ],
            'null-value' => [
                'column' => 'col1',
                'condition' => '=',
                'value' => NULL,
                'firstHaving' => true,
                'expected' => ' HAVING col1 = NULL',
            ],
            'int-value' => [
                'column' => 'col1',
                'condition' => '>',
                'value' => 10,
                'firstHaving' => true,
                'expected' => ' HAVING col1 > 10',
            ],
            'string-value' => [
                'column' => 'col1',
                'condition' => '!=',
                'value' => 'str',
                'firstHaving' => true,
                'expected' => " HAVING col1 != 'str'",
            ],
            'array-value' => [
                'column' => 'col1',
                'condition' => 'IN',
                'value' => [1, 2, 3],
                'firstHaving' => false,
                'expected' => " OR col1 IN ('1', '2', '3')",
            ],
        ];
    }
}
