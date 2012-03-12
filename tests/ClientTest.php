<?php
/**
 *
 *
 */
class ClientTest extends PHPUnit_Framework_TestCase
{
    public function testHead() 
    {
        $server = 'sgphp.project-p.jp/server.php';
        $thread_name = 'thread_E99B91E8AB87';

        $client = new Shingetsu_Client($server);
        $result = $client->head($thread_name, '-');
        $this->assertTrue(is_string($result)); 
    }
}
