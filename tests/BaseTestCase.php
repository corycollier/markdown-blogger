<?php

namespace MarkdownBlogger\Tests;

class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    public function getMockedObject($class, $methods = [])
    {
        return $this->getMockBuilder($class)
            ->disableOriginalConstructor()
            ->setMethods($methods)
            ->getMock();
    }


    public function getMethod($class, $method)
    {
        $method = new \ReflectionMethod($class, $method);
        $method->setAccessible(true);
        return $method;
    }

    public function getProperty($class, $property)
    {
        $property = new \ReflectionProperty($class, $property);
        $property->setAccessible(true);
        return $property;
    }

}
