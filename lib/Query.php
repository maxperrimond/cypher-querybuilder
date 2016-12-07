<?php

namespace MP\Cypher;

class Query
{
    /**
     * @var mixed[]
     */
    protected $parts = [];

    /**
     * @param mixed $part
     */
    protected function addPart($part)
    {
        $this->parts[] = $part;
    }

    /**
     * @return string
     */
    public function getQuery()
    {
        return implode(',', array_map(function ($part) {
            if ($part instanceof Query) {
                return $part->getQuery();
            }

            return (string) $part;
        }, $this->parts));
    }
}
