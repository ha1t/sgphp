<?php
// ディスク消費が大きいので他人のURLに振り向けてでもいいかも(Coral CDN)
// http://shingetsu.info/protocol/protocol-0.7.d1

$server = 'bbs.shingetsu.info:80/server.cgi';
$server = 'rep4649.ddo.jp:80/server.cgi';
$server = 'sg.sabaitiba.com:49494/server.cgi';
$server = '163.43.161.96:8000/server.cgi';
$server = 'sgphp.project-p.jp/server.php';

class Shingetsu_Client
{
    private $server;

    public function __construct($server)
    {
        $this->server = $server;
    }

    private function fetch($url)
    {
        $context = stream_context_create(
            array('http' => array(
                'method' => 'GET',
                'header' => 'User-Agent: shinGETsu/0.7 (sgphp/0.1)',
            ))
        );
        return trim(file_get_contents('http://' . $url, false, $context));
    }

    public function ping()
    {
        return $this->fetch($this->server . '/ping');
    }

    public function node()
    {
        return $this->fetch($this->server . '/node');
    }

    public function recent()
    {
        $response = $this->fetch($this->server . '/recent');
        $lines = explode("\n", $response);

        $files = array();
        foreach ($lines as $line) {
            if (strpos($line, '<>') === false) {
                continue;
            }
            list($timestamp, $id, $filename) = explode('<>', $line);
            $name = str_replace('thread_', '', $filename);
            $title = pack('H*', $name);
            $id2 = md5('thread_' . $filename);
            $files[] = compact('timestamp', 'id', 'filename', 'title', 'id2');
        }

        return $files;
    }

    public function head($filename, $timestamp = false)
    {
        $url = $this->server . "/head/{$filename}";
        $response = $this->fetch($url);
        var_dump($url, $response);
    }

    public function have($filename, $timestamp = false)
    {
        $url = $this->server . "/have/{$filename}";
        $response = $this->fetch($url);

        if ($response === "YES") {
            return true;
        } else if ($response === "NO") {
            return false;
        } else {
            throw new Exception($response);
        }
    }

    // timestampは必須
    public function get($filename, $timestamp = false)
    {
        $url = $this->server . "/get/{$filename}";
        if ($timestamp !== false) {
            $url .= "/{$timestamp}";
        }
        $response = trim(file_get_contents($url));

        return $response;
    }

    public function join($my_node)
    {
        $url = $this->server . "/join/{$my_node}";
        return $this->fetch($url);
    }
}

$s = new Shingetsu_Client($server);


//$result = $s->ping(); var_dump($result); exit;
//$result = $s->node(); var_dump($result); exit;

$my_node = str_replace('/', '+', ':80/server.php');
$result = $s->join($my_node); var_dump($result); exit;

//$result = $s->have('thread_6F70657261'); var_dump($result); exit;
//$result = $s->have('thread_E69CAC'); var_dump($result); exit;

//$files = $s->recent(); var_dump($files);

/*
foreach ($files as $file) {
    if ($s->have($file['filename'])) {
        $data = $s->get($file['filename'], '0-');
        file_put_contents('data/' . $file['filename'], $data);
        touch("data/{$file['filename']}", $file['timestamp']);
    }
}
 */

