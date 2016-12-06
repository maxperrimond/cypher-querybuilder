<?php

namespace MP\Cypher;

class Query
{
    /**
     * @var Query[]
     */
    protected $parts = [];

    /**
     * @param Query $part
     */
    protected function addPart(Query $part)
    {
        $this->parts[] = $part;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return implode(',', array_map(function (Query $part) {
            return $part->getQuery();
        }, $this->parts));
    }
}
