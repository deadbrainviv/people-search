<?php

namespace PeopleSearch;

use Secrets\Secret;

class Searcher
{
    /**
     * PeopleSearch\ResultCleaner
     * @var obejct
     */
    protected $standardizer;

    /**
     * JHED search service URL
     * Defaults to production
     * @var string
     */
    protected $url = "https://my.johnshopkins.edu/portalcontent/search/framework/service/people/peoplewebservice.cfc";

    public function __construct($options, $env)
    {
        if ($env == "staging") {
            $this->url = "https://stage.johnshopkins.edu/portalcontent/search/framework/service/people/peoplewebservice.cfc";
        }

        $this->standardizer = new ResultStandardizer();

        $this->options = (array) Secret::get("jhed") + $options;

        if (empty($this->options["ipaddress"])) {
            $this->options["ipaddress"] = $_SERVER["REMOTE_ADDR"];
        }
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

        $results = !empty($resp) ? wddx_deserialize($resp) : array();
        unset($results["WEBSERVICEKEY"]);

        if (isset($results["RECORDCOUNT"])) {
            $count = $results["RECORDCOUNT"];
            $records = $results["COLLECTION"];
        } else if (!empty($results)) {
            $count = 1;
            $records = array($results);
        }

        $records = is_array($records) ? array_map(array($this->standardizer, "clean"), $records) : $records;

        return array(
            "count" => $count,
            "records" => $records
        );
    }

}
