<?php
/**
 *
 *
 */
class storage
{
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
}
