<?php

namespace MP\Cypher\Query;

use MP\Cypher\Query;
use MP\Cypher\QueryBuilderException;

class Relation extends Query
{
    const LEFT = 'left';
    const RIGHT = 'right';
    const OMNI = 'omni';

    const RANGE_PATTERN = '^\*([0-9]+(\.{2}([0-9]+)?)?)?$';

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
    public $direction = self::OMNI;

    /**
     * @var string
     */
    public $range;

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
     *
     * @throws QueryBuilderException
     */
    public function node($alias = null, $type = null, array $properties = [])
    {
        if (!empty($this->parts)) {
            throw QueryBuilderException::nodeAlreadyDefined();
        }

        $this->addPart($node = new Node($alias, $type, $properties));

        return $node;
    }

    /**
     * @return Relation
     */
    public function right()
    {
        $this->direction = self::RIGHT;

        return $this;
    }

    /**
     * @return Relation
     */
    public function left()
    {
        $this->direction = self::LEFT;

        return $this;
    }

    /**
     * @return Relation
     */
    public function omni()
    {
        $this->direction = self::OMNI;

        return $this;
    }

    /**
     * @param string $range
     *
     * @return Relation
     *
     * @throws QueryBuilderException
     */
    public function setRange($range)
    {
        if (!preg_match('/' . self::RANGE_PATTERN . '/', $range)) {
            throw QueryBuilderException::invalidRangePattern();
        }

        $this->range = $range;

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

        $query = "[$query{$this->range}]";

        switch ($this->direction) {
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
                throw QueryBuilderException::invalidRelationDirection();
        }

        if (!empty($this->parts)) {
            /** @var Node $part */
            $part = current($this->parts);

            $query .= "{$part->getQuery()}";
        }

        return $query;
    }
}
