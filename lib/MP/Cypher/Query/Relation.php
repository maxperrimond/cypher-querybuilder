<?php

namespace MP\Cypher\Query;

use MP\Cypher\Query;

class Relation extends Query
{
    const LEFT = 'left';
    const RIGHT = 'right';
    const OMNI = 'omni';

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
     * @var string
     */
    public $way = self::OMNI;

    /**
     * @var string
     */
    public $length;

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
     * @return Node
     */
    public function node($alias = null, $type = null, array $properties = [])
    {
        // TODO: throw if already have node

        $this->addPart($node = new Node($alias, $type, $properties));

        return $node;
    }

    /**
     * @param string $way
     *
     * @return Relation
     */
    public function setWay($way)
    {
        if (!in_array($way, [self::LEFT, self::RIGHT, self::OMNI])) {
            // TODO: throw error
        }

        $this->way = $way;

        return $this;
    }

    /**
     * @param string $length
     *
     * @return Relation
     */
    public function pathLength($length)
    {
        // TODO: check pattern

        $this->length = $length;

        return $this;
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

        $query = "[$query{$this->length}]";

        switch ($this->way) {
            case self::RIGHT:
                $query = "-$query->";
                break;
            case self::LEFT:
                $query = "<-$query-";
                break;
            case self::OMNI:
                $query = "-$query-";
                break;
            default:
                // TODO: throw
        }

        if (!empty($this->parts)) {
            $query .= "{$this->parts[0]->getQuery()}";
        }

        return $query;
    }
}
