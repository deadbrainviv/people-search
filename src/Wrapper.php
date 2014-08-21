<?php

namespace PeopleSearch;

use Secrets\Secret;

class Wrapper
{

    public function __construct($options)
    {
        // find environment
        $env = defined("ENV") ? ENV : "local";

        // if local, get a proxy object
        if ($env === "local") {
            $this->people = new Proxy($options);
        }

        // otherwise, get a regular search object
        else {
            $this->people = new Searcher((array) Secret::get("jhed") + $options);
        }
    }

    public function setIpAddress($ip)
    {
        $this->people->setIpAddress($ip);
    }

    public function search($criteria = null)
    {
        return $this->people->search(urldecode($criteria));
    }

}
