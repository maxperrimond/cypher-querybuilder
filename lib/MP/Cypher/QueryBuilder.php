<?php

namespace MP\Cypher;

use MP\Cypher\Query;

class QueryBuilder
{
    /**
     * @var Query\Match[]
     */
    private $matches = [];

    /**
     * @var Query\OrderBy
     */
    private $orderBy;

    /**
     * @var Query\Where
     */
    private $where;

    /**
     * @var $skip
     */
    private $skip;

    /**
     * @var int
     */
    private $limit;

    /**
     * @return Query\Match
     */
    public function addMatch()
    {
        $match = new Query\Match();
        $this->matches[] = $match;

        return $match;
    }

    /**
     * @return Query\OptionalMatch
     */
    public function addOptionalMatch()
    {
        $match = new Query\OptionalMatch();
        $this->matches[] = $match;

        return $match;
    }

    /**
     * @return Query\Where
     */
    public function where()
    {
        $this->where = new Query\Where();

        return $this->where;
    }

    /**
     * @return Query\OrderBy
     */
    public function orderBy()
    {
        $this->orderBy = new Query\OrderBy();

        return $this->orderBy;
    }

    /**
     * @param int $skip
     *
     * @return QueryBuilder
     */
    public function skip($skip)
    {
        $this->skip = $skip;

        return $this;
    }

    /**
     * @param int $limit
     *
     * @return QueryBuilder
     */
    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param array ...$values
     *
     * @return string
     */
    public function getQuery(...$values)
    {
        $query = implode(" ", array_map(function (Query\Match $match) {
            return $match->getQuery();
        }, $this->matches));

        if ($this->where) {
            $query .= " {$this->where->getQuery()}";
        }

        $query .= " RETURN " . implode(',', $values);

        if ($this->orderBy) {
            $query .= " {$this->orderBy->getQuery()}";
        }

        if ($this->skip) {
            $query .= " SKIP {$this->skip}";
        }

        if ($this->limit) {
            $query .= " LIMIT {$this->limit}";
        }

        return $query;
    }
}
