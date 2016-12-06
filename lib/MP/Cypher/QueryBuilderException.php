<?php

namespace MP\Cypher;

class QueryBuilderException extends \Exception
{
    public static function relationAlreadyDefined()
    {
        return new self("The node have already a relation defined");
    }
}
