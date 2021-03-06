<?php
require_once dirname(__FILE__) . '/Shingetsu_Client.php';

/**
 * Shingetsu_Server
 *
 * @url http://shingetsu.info/protocol/protocol-0.7.d1
 * @url http://shingetsu.info/tech/cache
 */
class Shingetsu_Server
{
    public $nodelist = 'data/nodelist.txt';

    public function ping()
    {
        header('Content-Type: text/plain; charset=UTF-8');
        echo "PONG\n{$_SERVER['REMOTE_ADDR']}";
    }

    public function node()
    {
        header('Content-Type: text/plain; charset=UTF-8');
        $lines = file($this->nodelist);
        shuffle($lines);
        echo trim(current($lines));
    }

    public function recent($timestamp = false)
    {
        $items = array();
        foreach (glob('data/*') as $filename) {
            $encode_name = str_replace('thread_', '', basename($filename));
            $timestamp = filemtime($filename);
            $items[$timestamp] = array(
                'timestamp' => $timestamp,
                'id' => md5($filename),
                'filename' => basename($filename),
            );
        }
        ksort($items);

        $response = '';
        foreach ($items as $item) {
            $response .= "{$item['timestamp']}<>{$item['id']}<>{$item['filename']}" . PHP_EOL;
        }

        header('Content-Type: text/plain; charset=UTF-8');
        echo $response;
    }

    // @TODO 相手にpingが通るか確認する
    public function join($node, $remote_addr)
    {
        header('Content-Type: text/plain; charset=UTF-8');
        $node = str_replace('+', '/', $node);

        // ホスト名が省略されていた場合、REMOTE_ADDRを使う
        if ($node[0] === ':') {
            $node = $remote_addr . $node;
        }

        $lines = file($this->nodelist);
        if (!in_array($node, $lines)) {
            $lines[] = $node . "\n";
            $lines = array_unique($lines);
            file_put_contents($this->nodelist, implode('', $lines));
        }

        echo 'WELCOME';
    }

    // @TODO 相手にpingが通るか確認する
    public function bye($node, $remote_addr)
    {
        header('Content-Type: text/plain; charset=UTF-8');
        $node = str_replace('+', '/', $node);

        // ホスト名が省略されていた場合、REMOTE_ADDRを使う
        if ($node[0] === ':') {
            $node = $remote_addr . $node;
        }

        $lines = file($this->nodelist);
        foreach ($lines as $key => $line) {
            if ($line == $node) {
                unset($lines[$key]);
                break;
            }
        }
        file_put_contents($this->nodelist, implode('', $lines));

        echo 'BYEBYE';
    }

    public function have($thread_name)
    {
        header('Content-Type: text/plain; charset=UTF-8');
        foreach (glob('data/*') as $filename) {
            if ($thread_name == basename($filename)) {
                echo 'YES';
                return true;
            }
        }

        echo 'NO';
        return false;
    }

    // @TODO 各種引数に応じたレス
    public function head($thread_name, $timestamp, $id = false)
    {
        header('Content-Type: text/plain; charset=UTF-8');
        foreach (glob('data/*') as $filename) {
            if ($thread_name == basename($filename)) {
                $file = file($filename);
                $parts = explode('<>', end($file));
                echo "{$parts[0]}<>{$parts[1]}";
                return;
            }
        }
    }

    // @TODO 各種引数に応じたレスを返すように作りなおす
    public function get($thread_name, $timestamp, $id = false)
    {
        if (!file_exists('data/' . $thread_name)) {
            return false;
        }

        header('Content-Type: text/plain; charset=UTF-8');
        $fp = fopen('data/' . $thread_name, 'r');
        fpassthru($fp);
    }

    // @note /update/ファイル名/時刻/識別子/ノード名
    public function update($filename, $timestamp, $id, $node)
    {
        $node = str_replace('+', '/', $node);
        $client = new Shingetsu_Client($node);

        // 手持ちのファイルかどうか確認する
        // 持っていなければ受け取る || 持っていれば差分を取得
        if ($client->have($filename)) {
            $response = $client->get($filename, '-' . time());
        } else {
            $response = $client->get($filename, '-' . time());
        }

        $lines = explode("\n", $response);
        $parts = explode('<>', end($lines));

        file_put_contents('data/' . $filename, $response);
        $result = chmod("data/{$filename}", 0666);
        if ($result === false) {
            error_log('chmod failed:' . $filename);
        }
        $result = touch("data/{$filename}", $parts[0]);
        if ($result === false) {
            error_log('touch failed:' . $filename);
        }

        // ノード名を自分のものに変更して、他のノードに投げる
    }
}

