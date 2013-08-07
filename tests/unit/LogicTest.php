<?php
class LogicTest extends PHPUnit_Framework_TestCase
{
    public function testDecode()
    {
        $thread_name = 'thread_E99B91E8AB87';
        $name = pack('H*', str_replace('thread_', '', $thread_name));
        $this->assertEquals($name, '雑談');
    }
}
