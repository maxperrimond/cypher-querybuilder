<?php

namespace MP\Cypher\Query;

class OptionalMatch extends Match
{
    public function getQuery()
    {
        return sprintf('OPTIONAL %s', parent::getQuery());
    }
}
