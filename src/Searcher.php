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

  public function __construct($params, $env)
  {
    // if ($env == "staging") {
    //   $this->url = "https://stage.johnshopkins.edu/portalcontent/search/framework/service/people/peoplewebservice.cfc";
    // }

    $this->standardizer = new ResultStandardizer();
    $this->params = (array) Secret::get("jhed") + $params;
  }

  public function getUrl()
  {
    return $this->url . "?" . http_build_query($this->params);
  }

  public function search()
  {
    $c = curl_init();
    curl_setopt($c, CURLOPT_URL, $this->getUrl());
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($c, CURLOPT_REFERER, "http://www.jhu.edu");
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

    $per_page = isset($this->params["per_page"]) ? $this->params["per_page"] : 100;

    if (is_array($records) && count($records > $per_page)) {
      // slice off some records
      $records = array_slice($records, 0, $per_page);
      $count = $per_page;
    }

    $records = is_array($records) ? array_map(array($this->standardizer, "clean"), $records) : $records;

    $return = new \StdClass();
    $return->count = $count;
    $return->records = $records;

    return $return;
  }

}
