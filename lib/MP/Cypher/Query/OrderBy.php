<?php

namespace MP\Cypher\Query;

use MP\Cypher\Query;

class OrderBy extends Query
{
    const DESC = 'DESC';
    const ASC = 'ASC';

    /**
     * @var string|array
     */
    private $field;

    /**
     * @var string
     */
    private $order;

    /**
     * @param string|array $field
     * @param string       $order
     */
    public function __construct($field, $order = null)
    {
        $this->field = $field;
        $this->order = $order;
    }

    public function getQuery()
    {
        $field = (is_array($this->field)) ? implode(',', $this->field) : $this->field;

        $query = "ORDER BY $field";

        if ($this->order) {
            $query .= " $this->order";
        }

        return $query;
    }
}
