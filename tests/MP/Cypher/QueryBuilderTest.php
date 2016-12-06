<?php

namespace Tests\MP\Cypher;

use MP\Cypher\Query\Match;
use MP\Cypher\Query\OptionalMatch;
use MP\Cypher\Query\Relation;
use MP\Cypher\Query\Where;
use MP\Cypher\QueryBuilder;

class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testInstancing()
    {
        $qb = new QueryBuilder();

        $this->assertNotNull($qb);
        $this->assertInstanceOf(QueryBuilder::class, $qb);
    }

    public function testMatchQueryBuilder()
    {
        $match = new Match();

        $match
            ->node('u', 'user')
            ->relation('m', 'member')->setWay(Relation::RIGHT)
            ->node(null, 'lot')
            ->relation(null, 'parent')->pathLength('*0..')
            ->node(null, 'lot', [
                'id' => 'toto',
            ]);

        $this->assertEquals("MATCH (u:user)-[m:member]->(:lot)-[:parent*0..]-(:lot {id:'toto'})", $match->getQuery());
    }

    public function testOptionalMatchQueryBuilder()
    {
        $match = new OptionalMatch();

        $match
            ->node('u', 'user')
            ->relation('m', 'member')->setWay(Relation::RIGHT)
            ->node(null, 'lot')
            ->relation(null, 'parent')->pathLength('*0..')
            ->node(null, 'lot', [
                'id' => 'toto',
            ]);

        $this->assertEquals("OPTIONAL MATCH (u:user)-[m:member]->(:lot)-[:parent*0..]-(:lot {id:'toto'})", $match->getQuery());
    }

    public function testWhereQueryBuilder()
    {
        $where = new Where();

        $where->expr("t.name = 'foo'");

        $this->assertEquals("WHERE t.name = 'foo'", $where->getQuery());
    }

    public function testQueryBuilder()
    {
        $qb = new QueryBuilder();

        $qb->addMatch()->node('u', 'user', ['id' => 'foo'])->relation()->node('n');
        $qb->where()->expr("n.name = 'foo'");
        $qb->limit(100);
        $qb->skip(10);

        $this->assertEquals("MATCH (u:user {id:'foo'})-[]-(n) WHERE n.name = 'foo' RETURN u,n SKIP 10 LIMIT 100", $qb->getQuery('u', 'n'));
    }
}
