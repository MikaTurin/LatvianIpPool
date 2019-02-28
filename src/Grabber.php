<?php
namespace MikaTurin\LatvianIpPool;

class Grabber
{
    const URL = 'https://www.nic.lv/local.net';

    protected $timeoutConnect = 5;
    protected $timeoutRead = 5;


    public function __construct()
    {

    }

    public function process()
    {
        $ips = [];
        if ($file = fopen($source, 'rb')) {
            while (!feof($file)) {
                $line = trim(fgets($file));
                if (empty($line) || 0 === strpos($line, '#')) {
                    continue;
                }
                $ips[] = $line;
            }
            fclose($file);
        }
        $ips = array_unique($ips);
        if (empty($ips)) {
            die("# failed to get ip list\n");
        }
    }

    public function read($url)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeoutConnect);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeoutRead);

        $s = curl_exec($ch);
        curl_close($ch);

        return $s;
    }

}
