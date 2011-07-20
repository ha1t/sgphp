<?php
require_once 'Shingetsu_Server.php';

$server = new Shingetsu_Server();

if (!file_exists($server->nodelist)) {
    touch($server->nodelist);
    chmod($server->nodelist, 0666);
    file_put_contents($server->nodelist, "sg.sabaitiba.com\n");
}

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

$log_file = dirname(__FILE__) . '/data/log.txt';
if (!file_exists($log_file)) {
    touch($log_file);
    chmod($log_file, 0666);
}
$log = file_get_contents($log_file);
$log = $log . date('Y-m-d H:i:s') . "|{$_SERVER['REMOTE_ADDR']}|{$_SERVER['PATH_INFO']}\n";
file_put_contents($log_file, $log);

$command = $pathinfo[1];
if ($command === 'join' || $command === 'bye') {
    $server->$command($pathinfo[2], $_SERVER['REMOTE_ADDR']);
} else if ($command === 'get' || $command === 'head') {
    $id = false;
    if (isset($pathinfo[4])) {
        $id = $pathinfo[4];
    }
    $server->$command($pathinfo[2], $pathinfo[3], $id);
} else if ($command === 'update') {
    $node = $pathinfo[5];
    if ($node[0] == ':') {
        $node = $_SERVER['REMOTE_ADDR'] . $node;
    }
    $server->update($pathinfo[2], $pathinfo[3], $pathinfo[4], $node);
} else if (isset($pathinfo[2])) {
    $server->$command($pathinfo[2]);
} else {
    $server->$command();
}

