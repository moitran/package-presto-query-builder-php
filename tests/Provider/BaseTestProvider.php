<?php

namespace MoiTran\PrestoQueryBuilder\Tests\Provider;

/**
 * Trait BaseTestProvider
 * @package MoiTran\PrestoQueryBuilder\Tests\Provider
 */
trait BaseTestProvider
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
}
