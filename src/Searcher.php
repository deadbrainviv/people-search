<?php

namespace PeopleSearch;

class Searcher
{

    protected $url = "https://stage.johnshopkins.edu/portalcontent/search/framework/service/people/peoplewebservice.cfc";

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function setIpAddress($ip)
    {
        $this->options["ipaddress"] = $ip;
    }

    public function search($criteria)
    {
        $params = $this->options + array("criteria" => $criteria);
        $url = $this->url . "?" . http_build_query($this->options);

        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $resp = curl_exec($c);
        curl_close($c);

        $results = wddx_deserialize($resp);
        unset($results["WEBSERVICEKEY"]);

        return $results;
    }

}
