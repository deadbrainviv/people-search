<?php

namespace PeopleSearch;

class Wrapper
{

    public function __construct($options = array(), $env = "local")
    {
        $this->env = $env;
        if (!empty($options)) {
            $this->setup($options);
        }
    }

    public function setup($options)
    {
        // if local, get a proxy object
        if ($this->env === "local") {
            $this->people = new Proxy($options);
        }

        // otherwise, get a regular search object
        else {
            $this->people = new Searcher($options);
        }
    }

    public function search($criteria = null)
    {
        return $this->people->search();
    }

}
