<?php

namespace MoiTran\PrestoQueryBuilder\Tests;

use MoiTran\PrestoQueryBuilder\Base;

/**
 * Class BaseTest
 * @package MoiTran\PrestoQueryBuilder\Tests
 */
class BaseTest extends TestCases
{
    /**
     * @return array
     */
    public function providerRemoveSpecialChars()
    {
        return [
            'numeric' => [
                'input' => 111,
                'expected' => 111,
            ],
            'special-char' => [
                'input' => '"test"',
                'expected' => '\"test\"',
            ],
            'percent-char' => [
                'input' => '%test%',
                'expected' => '%test%',
            ],
            'array-value' => [
                'input' => [1, '"test"', "\n", "\r", "%test"],
                'expected' => ['1', '\"test\"', '\\n', '\\r', '%test'],
            ],
            'not-accept-value-type' => [
                'input' => new \stdClass(),
                'expected' => new \stdClass(),
            ],
        ];
    }

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
