<?php

namespace PeopleSearch;

class Proxy
{

    protected $url = "http://staging.jhu.edu/jhed-proxy";

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function getUrl()
    {
        return $this->url . "?" . http_build_query($this->options);
    }

    public function search()
    {
        $c = curl_init($this->getUrl());
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($c);
        curl_close($c);

        $results = !empty($resp) ? json_decode($resp) : array();

        return $results;
    }

}
