<?php

namespace PeopleSearch;

class Proxy
{

    public $url = "http://staging.jhu.edu/jhed-proxy";

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function setIpAddress($ip)
    {
        $this->options["ipaddress"] = $ip;
    }

    public function getUrl()
    {
        return $this->url . "?" . http_build_query($this->options);
    }

    public function search($criteria = null)
    {
        if (!empty($criteria)) {
            $this->options->criteria = $criteria;
        }

        $url = $this->getUrl();

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($c);
        curl_close($c);

        $results = !empty($resp) ? json_decode($resp) : array();

        return $results;
    }

}
