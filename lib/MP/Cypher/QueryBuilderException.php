<?php

namespace MP\Cypher;

class QueryBuilderException extends \Exception
{
    /**
     * @internal
     *
     * @return QueryBuilderException
     */
    public static function relationAlreadyDefined()
    {
        return new self("The node have already a relation defined");
    }

    /**
     * @internal
     *
     * @return QueryBuilderException
     */
    public static function nodeAlreadyDefined()
    {
        return new self("The relation have already a node defined");
    }

    /**
     * @internal
     *
     * @return QueryBuilderException
     */
    public static function invalidRelationDirection()
    {
        return new self("The relation direction is invalid");
    }

    /**
     * @internal
     *
     * @return QueryBuilderException
     */
    public static function invalidRangePattern()
    {
        return new self("The given range is invalid, it should be like: '*' or '*0' or '*1..' or '*3..5'");
    }
}
