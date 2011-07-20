<?php
// ディスク消費が大きいので他人のURLに振り向けてでもいいかも(Coral CDN)
// http://shingetsu.info/protocol/protocol-0.7.d1

require_once 'Shingetsu_Client.php';

$my_server = 'sgphp.project-p.jp/server.php';
$server = '163.43.161.96:8000/server.cgi';
$server = 'sg.sabaitiba.com:49494/server.cgi';
$server = $my_server;
$server = 'rep4649.ddo.jp:80/server.cgi';

function get_thread($s, $filename)
{
    // var_dump($s->have($filename)); exit;

    if (!$s->have($filename)) {
        var_dump('not found.');
        exit;
    }

    $data = $s->get($filename, '0-');
    file_put_contents('data/' . $filename, $data);

    $lines = explode("\n", $data);
    $timestamp = current(explode('<>', end($lines)));
    touch('data/' . $filename, $timestamp);
}

// my_serverにserverの隣接ノードをjoinさせる
function add_node($server, $my_server)
{
    $s = new Shingetsu_Client($server);
    $result = $s->node(); var_dump($result);
    $ms = new Shingetsu_Client($my_server);
    $result = $ms->join($result); var_dump($result); exit;
}

function crawl($s)
{
    $files = $s->recent();
    rsort($files);

    $break_limit = 5;
    foreach ($files as $file) {
        echo date('Y-m-d H:i:s', $file['timestamp']) . $file['filename'] . PHP_EOL;
        if ($break_limit < 1) {
            return;
        }

        if ($s->have($file['filename'])) {
            $data = $s->get($file['filename'], '0-');
            file_put_contents("data/{$file['filename']}", $data);
            chmod("data/{$file['filename']}", 0666);
            touch("data/{$file['filename']}", $file['timestamp']);
            sleep(1);
        }
        $break_limit -= 1;
    }
}

$s = new Shingetsu_Client($my_server);

//$result = $s->ping(); var_dump($result); exit;

//$node = $s->node(); var_dump($node); exit;
crawl(new Shingetsu_Client($s->node()));

//$my_node = str_replace('/', '+', ':80/server.php');
//$result = $s->join($my_node); var_dump($result); exit;

//$result = $s->have('thread_6F70657261'); var_dump($result); exit;
//$result = $s->have('thread_E69CAC'); var_dump($result); exit;

//get_thread($s, 'thread_503250');


