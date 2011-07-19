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
        echo "PONG\n{$_SERVER['REMOTE_ADDR']}";
    }

    public function node()
    {
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
    public function join($node)
    {
        $node = str_replace('+', '/', $node);

        $lines = file('nodelist.txt');
        if (!in_array($node, $lines)) {
            $lines[] = $node;
            file_put_contents('nodelist.txt', implode('', $lines));
        }

        echo 'WELCOME';
    }

    // @TODO 相手にpingが通るか確認する
    public function bye($node)
    {
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

