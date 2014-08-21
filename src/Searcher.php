<?php

namespace PeopleSearch;

class Searcher
{

    public $url = "https://stage.johnshopkins.edu/portalcontent/search/framework/service/people/peoplewebservice.cfc";

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
        $params = $this->options;

        if (!empty($criteria)) {
            $this->options["criteria"] = $criteria;
        }

        $url = $this->getUrl();

        echo $url; die();

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($c);
        curl_close($c);

        $results = !empty($resp) ? wddx_deserialize($resp) : array();
        unset($results["WEBSERVICEKEY"]);

        return $results;
    }

}
