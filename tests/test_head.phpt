--TEST--
shingetsu client: head method test
--FILE--
<?php
require_once dirname(dirname(__FILE__)) . '/Shingetsu_Client.php';

$server = 'sgphp.project-p.jp/server.php';
$thread_name = 'thread_E99B91E8AB87';

$s = new Shingetsu_Client($server);
$result = $s->head($thread_name, '-');
var_dump($result);
--EXPECT--

