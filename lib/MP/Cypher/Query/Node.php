<?php

namespace MP\Cypher\Query;

use MP\Cypher\Query;
use MP\Cypher\QueryBuilderException;

class Node extends Query
{
    /**
     * @var null|string
     */
    private $alias;

    /**
     * @var array|null|string
     */
    private $type;

    /**
     * @var array
     */
    private $properties;

    /**
     * @param string|null       $alias
     * @param string|array|null $type
     * @param array             $properties
     */
    public function __construct($alias = null, $type = null, array $properties = [])
    {
        $this->alias = $alias;
        $this->type = $type;
        $this->properties = $properties;
    }

    /**
     * @param string|null       $alias
     * @param string|array|null $type
     * @param array             $properties
     *
     * @return Relation
     *
     * @throws QueryBuilderException
     */
    public function relation($alias = null, $type = null, array $properties = [])
    {
        if (!empty($this->parts)) {
            throw QueryBuilderException::relationAlreadyDefined();
        }

        $this->addPart($relation = new Relation($alias, $type, $properties));

        return $relation;
    }

    /**
     * @param string|null       $alias
     * @param string|array|null $type
     * @param array             $properties
     *
     * @return Node
     *
     * @throws QueryBuilderException
     */
    public function node($alias = null, $type = null, array $properties = [])
    {
        if (!empty($this->parts)) {
            throw QueryBuilderException::relationAlreadyDefined();
        }

        $this->addPart($node = new Node($alias, $type, $properties));

        return $node;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery()
    {
        $query = ($this->alias) ? "$this->alias" : "";

        if ($this->type) {
            $query .= ":";
            $query .= (is_array($this->type)) ? implode('|', $this->type) : $this->type;
        }

        if (!empty($this->properties)) {
            $properties = [];
            foreach ($this->properties as $key => $value) {
                $property = "$key:";
                if (is_string($value)) {
                    $property .= "'$value'";
                } else {
                    $property .= "$value";
                }
                $properties[] = $property;
            }
            $query .= sprintf(' {%s}', implode(',', $properties));
        }

        $query = "($query)";

        if (!empty($this->parts)) {
            $nextPart = $this->parts[0];

            if ($nextPart instanceof Relation) {
                $query .= "{$nextPart->getQuery()}";
            } elseif ($nextPart instanceof Node) {
                $query .= "-->{$nextPart->getQuery()}";
            }
        }

        return $query;
    }
}
