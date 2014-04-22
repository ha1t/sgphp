<?php
/**
 *
 *
 */

// 読み込みを行うファイルサイズの上限を設定する。これ以上大きいファイルは読み込まない。
define('LIMIT_FILESIZE', 1024 * 1024 * 20);

define('SERVER_ADDRESS', 'sgphp.project-p.jp/server.php');

/*
$my_server = 'sgphp.project-p.jp/server.php';
$server = '163.43.161.96:8000/server.cgi';
$server = 'sg.sabaitiba.com:49494/server.cgi';
$server = $my_server;
$server = 'rep4649.ddo.jp:80/server.cgi';
 */

// 手元に置きたくないスレッドを指定できる。
// 正規表現patternで記述する。
$ignore_thread_name = array(
    'ニュース',
);

function is_ignore()
{
}
