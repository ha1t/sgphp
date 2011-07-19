<?php
/**
 * Shingetsu_Server
 *
 * @url http://shingetsu.info/protocol/protocol-0.7.d1
 */
class Shingetsu_Server
{
    public function ping()
    {
        echo "PONG\n{$_SERVER['REMOTE_ADDR']}";
    }

    public function node()
    {
        $lines = file('nodelist.txt');
        shuffle($lines);
        echo current($lines);
    }

    public function recent()
    {
    }

    public function have()
    {
    }
}

