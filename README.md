Presto Query Builder
===================

This package provides a set of classes and methods that is able to programmatically build presto queries.

[![Latest Stable Version](https://poser.pugx.org/moitran/package-presto-query-builder-php/v/stable)](https://packagist.org/packages/moitran/package-presto-query-builder-php)
[![Latest Unstable Version](https://poser.pugx.org/moitran/package-presto-query-builder-php/v/unstable)](https://packagist.org/packages/moitran/package-presto-query-builder-php)
[![Build Status](https://travis-ci.org/moitran/package-presto-query-builder-php.svg?branch=master)](https://travis-ci.org/moitran/package-presto-query-builder-php)
[![codecov](https://codecov.io/gh/moitran/package-presto-query-builder-php/branch/master/graphs/badge.svg)](https://codecov.io/gh/moitran/package-presto-query-builder-php)
[![License](https://poser.pugx.org/moitran/package-presto-query-builder-php/license)](https://packagist.org/packages/moitran/package-presto-query-builder-php)
[![composer.lock](https://poser.pugx.org/moitran/package-presto-query-builder-php/composerlock)](https://packagist.org/packages/moitran/package-presto-query-builder-php)

- [Installation](#installation)
- [Usage](#usage)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)


Installation
------------

``` bash
$ composer require moitran/package-presto-query-builder
```

Usage
-----

Some examples:

1. 
    ```php
        $query = new MoiTran\PrestoQueryBuilder\Query();
        $queryStr = $query->select('*')
           ->from('table1')
           ->whereAnd('col1', 'IS', NULL)
           ->whereAnd('col2', '=', 'value2')
           ->whereOr('col3', 'LIKE', '%test%')
           ->orderBy('col1', 'DESC')
           ->orderBy('col2', 'ASC')
           ->limit(10)
           ->getQueryStr();
    ```

    Output: 
    ```sql
       SELECT * FROM (table1) WHERE col1 IS NULL AND col2 = 'value2' OR col3 LIKE '%test%' ORDER BY col1 DESC, col2 ASC LIMIT 10

    ```

2. 
    ```php
        $whereAndGroup = new MoiTran\PrestoQueryBuilder\WhereGroup();
        $whereAndGroup->whereAnd('col4', '!=', 'value4');
        $whereAndGroup->whereOr('col5', 'NOT LIKE', 'value5%');
    
        $whereOrGroup = new MoiTran\PrestoQueryBuilder\WhereGroup();
        $whereOrGroup->whereAnd('col6', '=', 'value6');
        $whereOrGroup->whereOr('col7', 'IN', ['value7', 'value8']);
    
        $query = new MoiTran\PrestoQueryBuilder\Query();
        $queryStr = $query->select([
            'col1',
            'col2' => 'colalias2',
            'col3' => 'colalias3'
        ])
            ->from('table1')
            ->whereAnd('col1', 'IS', NULL)
            ->whereAnd('col2', '=', 'value2')
            ->whereOr('col3', 'LIKE', '%test%')
            ->whereAndGroup($whereAndGroup)
            ->whereOrGroup($whereOrGroup)
            ->groupBy(['col1', 'col2', 'col3'])
            ->orderBy('col1', 'DESC')
            ->orderBy('col2', 'ASC')
            ->limit(10)
            ->getQueryStr();
    ```

    Output: 
    ```sql
       SELECT col1,
              col2 AS colalias2,
              col3 AS colalias3
       FROM (table1)
       WHERE col1 IS NULL
         AND col2 = 'value2'
         OR col3 LIKE '%test%'
         AND (col4 != 'value4'
              OR col5 NOT LIKE 'value5%')
         OR (col6 = 'value6'
             OR col7 IN ('value7',
                         'value8'))
       GROUP BY col1,
                col2,
                col3
       ORDER BY col1 DESC,
                col2 ASC
       LIMIT 10
    ```

3. 
    ```php
        $query = new MoiTran\PrestoQueryBuilder\Query();
        $queryStr = $query->select([
            'a.col1' => 'acol1',
            'a.col2' => 'acol2',
            'b.col1' => 'bcol1',
            'b.col2' => 'bcol2',
        ])
            ->from('table1', 'a')
            ->leftJoin('table2', 'b')
            ->on('a.id', '=', 'b.a_id')
            ->whereAnd('a.col1', '=', 'value1')
            ->limit(10)
            ->getQueryStr();
    ```

    Output: 
    ```sql
       SELECT a.col1 AS acol1,
              a.col2 AS acol2,
              b.col1 AS bcol1,
              b.col2 AS bcol2
       FROM (table1) AS a
       LEFT JOIN (table2) AS b ON a.id = b.a_id
       WHERE a.col1 = 'value1'
       LIMIT 10
    ```

Testing
-------

``` bash
$ vendor/bin/phpunit --coverage-html=cov/ tests/
```


Contributing
------------

* [Moi Tran](https://github.com/moitran)


License
-------

This package is under [MIT License](https://github.com/moitran/package-presto-query-builder-php/blob/master/LICENSE)