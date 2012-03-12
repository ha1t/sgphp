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

    public function testUpdate() 
    {
        //$my_server = 'sgphp.project-p.jp';
        //$s = new Shingetsu_Client($my_server);

        //$re = file_get_contents('http://sgphp.project-p.jp/server.php/update/thread_E69CAC/1311085121/f9e7ab5f2dc9b4293fa8a17a1cdc9c94/sgphp.project-p.jp:80+server.php');
        //var_dump($re);
    }
}
