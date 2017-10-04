<?php

namespace PeopleSearch;

class Wrapper
{

    public function __construct($params = array(), $env = "local")
    {
        $this->env = $env;
        $this->setup($params);
    }

    public function setup($params)
    {
        // if local, get a proxy object
        if ($this->env === "local") {
            $this->people = new Proxy($params);
        }

        // otherwise, get a regular search object
        else {
            $this->people = new Searcher($params, $this->env);
        }

    }

    public function search()
    {
        return $this->people->search();
    }

}
