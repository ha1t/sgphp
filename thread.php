<?php
// @TODO ページャ
// @TODO latest50件ごと表示
// @TODO 特定レスの表示

// @note スレッド内のレス番は、識別子の先頭8桁。
function get_thread($title)
{
    $filename = 'data/thread_' . strtoupper(bin2hex($title));

    if (!file_exists($filename)) {
        return false;
    }

    $raw_data = file_get_contents($filename);

    $threads = array();
    $remove_ids = array();
    $lines = explode("\n", $raw_data);
    foreach ($lines as $line) {
        $parts = explode('<>', $line);
        $thread = array(
            'name' => '',
            'timestamp' => array_shift($parts),
            'id' => array_shift($parts)
        );
        $thread['datetime'] = date('Y-m-d H:i:s', $thread['timestamp']);

        while(count($parts) > 0) {
            if (strpos($parts[0], 'name:') === 0) {
                $thread['name'] = array_shift($parts);
            } else if (strpos($parts[0], 'mail:') === 0) {
                $thread['mail'] = array_shift($parts);
            } else if (strpos($parts[0], 'body:') === 0) {
                $thread['body'] = substr(array_shift($parts), 5);
            } else if (strpos($parts[0], 'remove_stamp:') === 0) {
                array_shift($parts);
            } else if (strpos($parts[0], 'remove_id:') === 0) {
                $remove_ids[] = str_replace('remove_id:', '', array_shift($parts));
            } else if (strpos($parts[0], 'attach:') === 0) {
                $thread['attach'] = substr(array_shift($parts), 7);
            } else if (strpos($parts[0], 'suffix:') === 0) {
                $thread['suffix'] = substr(array_shift($parts), 7);
            } else if (strpos($parts[0], 'sign:') === 0) {
                // implement yet
                array_shift($parts);
            } else if (strpos($parts[0], 'pubkey:') === 0) {
                // implement yet
                array_shift($parts);
            } else if (strpos($parts[0], 'target:') === 0) {
                // implement yet
                array_shift($parts);
            } else {
                var_dump(__LINE__,$parts);exit;
                // throw new Exception();
            }
        }

        $threads[$thread['id']] = $thread;
    }

    // remove_id指定されたレスの削除
    foreach ($remove_ids as $remove_id) {
        if (isset($threads[$remove_id])) {
            unset($threads[$remove_id]);
        }
    }

    return $threads;
}

$pathinfo = explode('/', $_SERVER['PATH_INFO']);
$title = $pathinfo[1];
$threads = get_thread($title);

if (isset($pathinfo[2])) {
    $ident_prefix = $pathinfo[2];
    foreach (array_keys($threads) as $md5) {
        if ($ident_prefix == substr($md5, 0, 8)) {
            $threads = array($md5 => $threads[$md5]);
        }
    }
}

header('Content-Type: text/html; charset=UTF-8');
if ($threads === false) {
    exit('page not found.');
}
?>
<html>
<head>
  <title><?php echo $title; ?></title>
</head>
<body>
  <h1><?php echo $title; ?></h1>
  <hr>
  <?php foreach($threads as $thread): ?>
    <?php echo '<a href="/thread.php/' . $title . '/' . substr($thread['id'], 0, 8) . '">' . substr($thread['id'], 0, 8) . '</a>' ?>
    <span style="color:green;"><?php echo $thread['datetime']; ?></span><br />
    <?php echo $thread['body']; ?><br />
<?php if (isset($thread['attach']) && $thread['suffix'] == 'jpg') echo "<img src=\"data:image/jpg;base64,{$thread['attach']}\">"; ?>
    <hr>
  <?php endforeach; ?>
</body>
</html>
