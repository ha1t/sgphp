<?php
/**
 *
 *
 */

function get_threads()
{
    $items = array();
    foreach (glob('data/*') as $filename) {
        $encode_name = str_replace('thread_', '', basename($filename));
        $items[] = array(
            'title' => pack('H*', $encode_name),
            'timestamp' => filectime($filename),
            'datetime' => date('Y-m-d H:i:s', filectime($filename)),
        );
    }

    return $items;
}

$items = get_threads();
header('Content-Type: text/html; charset=UTF-8');
?>
<html>
<body>
<h1>sgphp - 新月PHP実装</h1>
<h2>TODO</h2>
<ul>
  <li>Serverの実装</li>
  <li>Serverの実装:投稿ができるように</li>
  <li>Threadのパーサを強化:attachを表示できるように</li>
</ul>
<h2>スレッド一覧</h2>
<ul>
<?php foreach ($items as $item): ?>
<?php echo "<li><a href=\"thread.php/{$item['title']}\">{$item['title']}</a>&nbsp;{$item['datetime']}</li>" . PHP_EOL; ?>
<?php endforeach; ?>
</ul>
</body>
</html>
