<?php

namespace MP\Cypher\Query;

use MP\Cypher\Query;

class Match extends Query
{
    /**
     * @param string|null       $alias
     * @param string|array|null $type
     * @param array             $properties
     *
     * @return Node
     */
    public function addNode($alias = null, $type = null, array $properties = [])
    {
        $this->addPart($node = new Node($alias, $type, $properties));

        return $node;
    }

    public function getQuery()
    {
        return sprintf('MATCH %s', parent::getQuery());
    }
}
