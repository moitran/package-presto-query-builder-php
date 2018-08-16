<?php

/**
 * Class ExampleTest
 */
class ExampleTest extends \PHPUnit\Framework\TestCase
{
    public function testIndex()
    {
        $this->assertEquals((new \MoiTran\PrestoQueryBuilder\Example())->index(), 1);
    }
}
