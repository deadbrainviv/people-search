<?php

namespace PeopleSearch;

class Proxy
{

  protected $url = "https://staging.jhu.edu/jhed-proxy/";

  public function __construct($params)
  {
    $this->params = $params;
  }

  public function search()
  {
    $url = $this->url . "?" . http_build_query($this->params);

    $c = curl_init($url);
    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
    $resp = curl_exec($c);
    curl_close($c);

    $results = !empty($resp) ? json_decode($resp) : array();

    return $results;
  }

}
