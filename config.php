<?php
/**
 *
 *
 */

// 読み込みを行うファイルサイズの上限を設定する。これ以上大きいファイルは読み込まない。
define('LIMIT_FILESIZE', 1024 * 1024 * 20);

// 手元に置きたくないスレッドを指定できる。
// 正規表現patternで記述する。
$ignore_thread_name = array(
    'ニュース',
);

function is_ignore()
{
}
