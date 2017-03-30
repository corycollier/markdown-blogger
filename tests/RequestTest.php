<?php

namespace MarkdownBlogger\Tests;

class RequestTest extends BaseTestCase
{
    /**
     * @dataProvider provideGet
     */
    public function testGet($expected, $key, $data, $exception = false)
    {
        $sut = $this->getMockedObject('MarkdownBlogger\Request', ['getData']);

        $sut->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($data));

        if ($exception) {
            $this->expectException($exception);
        }

        $result = $sut->get($key);
        $this->assertEquals($expected, $result);
    }

    public function provideGet()
    {
        $data = [
            'key1' => 'expected1',
            'key2' => 'expected2',
            'key3' => 'expected3',
            'key4' => 'expected4',
            'key5' => 'expected5',
            'key6' => 'expected6',
        ];

        return [
            'expect the 1st value' => [
                'expected'  => 'expected1',
                'key'       => 'key1',
                'data'      => $data,
                'exception' => false,
            ],

            'expect the 3rd value' => [
                'expected'  => 'expected3',
                'key'       => 'key3',
                'data'      => $data,
                'exception' => false,
            ],

            'expect an exception' => [
                'expected'  => '',
                'key'       => 'bad-key',
                'data'      => $data,
                'exception' => '\OutOfRangeException',
            ],

        ];
    }
}
