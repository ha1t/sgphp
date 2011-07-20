<?php
// http://shingetsu.info/protocol/protocol-0.7.d1

/**
 * Shingetsu_Client
 */
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
        var_dump(get_headers('http://' . $url)); exit;
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
        $response = $this->fetch($url);

        return $response;
    }

    public function join($my_node)
    {
        $url = $this->server . "/join/{$my_node}";
        return $this->fetch($url);
    }
}
