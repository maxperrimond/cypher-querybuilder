<?php

namespace MP\Cypher\Query;

use MP\Cypher\Query;

class OrderBy extends Query
{
    const DESC = 'DESC';
    const ASC = 'ASC';

    /**
     * @var string
     */
    private $order;

    /**
     * @param string $field
     *
     * @return OrderBy
     */
    public function addField($field)
    {
        $this->addPart($field);

        return $this;
    }

    /**
     * @return OrderBy
     */
    public function desc()
    {
        $this->order = self::DESC;

        return $this;
    }

    /**
     * @return OrderBy
     */
    public function asc()
    {
        $this->order = self::ASC;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery()
    {
        $fields = parent::getQuery();

        $query = "ORDER BY $fields";

        if ($this->order) {
            $query .= " $this->order";
        }

        return $query;
    }
}
