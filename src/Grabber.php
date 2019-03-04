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

    public function get()
    {
        return $this->parse($this->read(self::URL));
    }

    public function parse($s)
    {
        $ips = array();
        $r = preg_split("/\r\n|\n|\r/", $s);
        unset($s);

        if (empty($r)) {
            throw new FailureException('result array is empty');
        }

        foreach ($r as $line) {
                $line = trim($line);
                if (empty($line) || 0 === strpos($line, '#')) {
                    continue;
                }
                $ips[] = $line;
        }
        
        unset($r);
        $ips = array_unique($ips);


        if (empty($ips)) {
            throw new FailureException('no ip found in result array');
        }

        return $ips;
    }

    protected function read($url)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeoutConnect);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeoutRead);

        $s = curl_exec($ch);
        curl_close($ch);
        
        if (empty($s)) {
            throw new FailureException('http answer is empty');
        }

        return $s;
    }

}
