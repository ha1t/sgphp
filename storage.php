<?php
/**
 *
 *
 */
class Storage
{
    // ファイル名一覧を抽出して、ファイル名からスレッド名を取り出す
    public static function getThreads()
    {
        $items = array();
        $files = glob("data/thread_*");
        if ($files) foreach ($files as $filename) {
            $encode_name = str_replace('thread_', '', basename($filename));
            $timestamp = filemtime($filename);
            $items[$timestamp] = array(
                'title' => pack('H*', $encode_name),
                'timestamp' => $timestamp,
                'datetime' => date('Y-m-d H:i:s', $timestamp),
            );
        }

        krsort($items);
        return $items;
    }

    // タイトルを元にファイルを取り出しパースして返す
    public static function getThread($title)
    {
        $filename = 'data/thread_' . strtoupper(bin2hex($title));

        if (!file_exists($filename)) {
            return false;
        }

        // 20M以上のファイルの場合false
        $filesize = filesize($filename); 
        if ($filesize > (1024 * 1024 * 20)) {
            return false;
        }

        $raw_data = file_get_contents($filename);
        $threads = self::parse($raw_data); 

        return $threads;
    }

    // スレッドデータをパースして返す
    private static function parse($raw_data) 
    {
        $threads = array();
        $remove_ids = array();
        $lines = explode("\n", $raw_data);
        foreach ($lines as $line) {
            $parts = explode('<>', $line);
            $thread = array(
                'name' => '',
                'timestamp' => array_shift($parts),
                'id' => array_shift($parts),
                'body' => '',
            );
            $thread['datetime'] = date('Y-m-d H:i:s', $thread['timestamp']);

            while (count($parts) > 0) {
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
}
