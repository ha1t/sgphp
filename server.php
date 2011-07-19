<?php

require_once 'Shingetsu_Server.php';

$server = new Shingetsu_Server();

$support_method = array('ping', 'node', 'have');
$pathinfo = explode('/', $_SERVER['PATH_INFO']);
if (in_array($pathinfo[1], $support_method)) {
    $command = $pathinfo[1];
    $server->$command();
} else {
    exit('command not found.');
}

