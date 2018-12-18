<?php

namespace MoiTran\PrestoQueryBuilder\Tests;

/**
 * Class Base
 * @package MoiTran\PrestoQueryBuilder\Tests
 */
class TestCases extends \PHPUnit\Framework\TestCase
{
    /**
     * @param $object
     * @param $property
     * @param $value
     *
     * @return bool
     * @throws \ReflectionException
     */
    public function setPrivateProp(&$object, $property, $value)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $prop = $reflection->getProperty($property);
        $prop->setAccessible(true);
        $prop->setValue($object, $value);

        return true;
    }
}
