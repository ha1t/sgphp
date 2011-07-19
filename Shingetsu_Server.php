<?php
/**
 * Shingetsu_Server
 *
 * @url http://shingetsu.info/protocol/protocol-0.7.d1
 */
class Shingetsu_Server
{
    public function ping()
    {
        header('Content-Type: text/plain; charset=UTF-8');
        echo "PONG\n{$_SERVER['REMOTE_ADDR']}";
    }

    public function node()
    {
        header('Content-Type: text/plain; charset=UTF-8');
        $lines = file('nodelist.txt');
        shuffle($lines);
        echo current($lines);
    }

    public function recent()
    {
        foreach (glob('data/*') as $file) {
        }
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

        $lines = file('nodelist.txt');
        if (!in_array($node, $lines)) {
            $lines[] = "\n" . $node;
            file_put_contents('nodelist.txt', implode('', $lines));
        }

        echo 'WELCOME';
    }

    // @TODO 相手にpingが通るか確認する
    public function bye($node)
    {
        header('Content-Type: text/plain; charset=UTF-8');
        $node = str_replace('+', '/', $node);

        $lines = file('nodelist.txt');
        foreach ($lines as $key => $line) {
            if ($line == $node) {
                unset($lines[$key]);
            }
        }
        file_put_contents('nodelist.txt', implode('', $lines));

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
}

