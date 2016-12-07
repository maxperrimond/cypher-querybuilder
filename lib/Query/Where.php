<?php

namespace MP\Cypher\Query;

use MP\Cypher\Query;

class Where extends Query
{
    /**
     * @var string
     */
    private $expr;

    public function expr($expr)
    {
        $this->expr = $expr;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery()
    {
        return "WHERE {$this->expr}";
    }
}
