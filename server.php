<?php

require_once 'Shingetsu_Server.php';

$server = new Shingetsu_Server();

$support_method = array(
    'ping', 'node',
    'join', 'bye',
    'have', 'get',
    'head', 'update',
    'recent',
);

$pathinfo = explode('/', $_SERVER['PATH_INFO']);

if (!in_array($pathinfo[1], $support_method)) {
    exit('method not support.');
}

$log_file = dirname(__FILE__) . '/log.txt';
$log = file_get_contents($log_file);
$log = $log . date('Y-m-d H:i:s') . "|{$_SERVER['REMOTE_ADDR']}:{$_SERVER['REMOTE_PORT']}|" . $_SERVER['PATH_INFO'] . "\n";
file_put_contents($log_file, $log);

$command = $pathinfo[1];
if (isset($pathinfo[2])) {
    $server->$command($pathinfo[2]);
} else {
    $server->$command();
}

