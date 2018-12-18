<?php

namespace MoiTran\PrestoQueryBuilder\Tests;

use MoiTran\PrestoQueryBuilder\Base;
use MoiTran\PrestoQueryBuilder\Tests\Provider\BaseTestProvider;

/**
 * Class BaseTest
 * @package MoiTran\PrestoQueryBuilder\Tests
 */
class BaseTest extends TestCases
{
    use BaseTestProvider;

    /**
     * @param $input
     * @param $expected
     *
     * @dataProvider providerRemoveSpecialChars
     */
    public function testRemoveSpecialChars($input, $expected)
    {
        /**
         * @var Base $mock
         */
        $mock = $this->getMockBuilder(Base::class)
            ->getMockForAbstractClass();

        $actual = $mock->removeSpecialChars($input);

        $this->assertEquals($expected, $actual);
    }
}
