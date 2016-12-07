<?php

namespace Tests\MP\Cypher;

use MP\Cypher\Query\Match;
use MP\Cypher\Query\Node;
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
            ->addNode('u', 'user')
            ->relation('m', 'member')->right()
            ->node(null, 'lot')
            ->relation(null, 'parent')->setRange('*3')
            ->node(null, 'lot', [
                'id' => 'toto',
            ]);

        $this->assertEquals("MATCH (u:user)-[m:member]->(:lot)-[:parent*3]-(:lot {id:'toto'})", $match->getQuery());
    }

    public function testOptionalMatchQueryBuilder()
    {
        $match = new OptionalMatch();

        $match
            ->addNode('u', 'user')
            ->relation('m', 'member')->right()
            ->node(null, 'lot')
            ->relation(null, 'parent')->setRange('*2')
            ->node(null, 'lot', [
                'id' => 'toto',
            ]);

        $this->assertEquals("OPTIONAL MATCH (u:user)-[m:member]->(:lot)-[:parent*2]-(:lot {id:'toto'})", $match->getQuery());
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

        $qb->addMatch()->addNode('u', 'user', ['id' => 'foo'])->relation()->node('n');
        $qb->where()->expr("n.name = 'foo'");
        $qb->orderBy()->addField('n.id')->addField('n.created_at')->desc();
        $qb->limit(100);
        $qb->skip(10);

        $this->assertEquals("MATCH (u:user {id:'foo'})-[]-(n) WHERE n.name = 'foo' RETURN u,n ORDER BY n.id,n.created_at DESC SKIP 10 LIMIT 100", $qb->getQuery('u', 'n'));
    }

    public function testRelationRange()
    {
        $relation = new Relation();
        $relation->setRange('*');
        $relation->setRange('*2');
        $relation->setRange('*0..');
        $relation->setRange('*1..10');
        $relation->setRange('*10..34');
    }

    /**
     * @expectedException \MP\Cypher\QueryBuilderException
     */
    public function testNodeException()
    {
        $node = new Node();
        $node->relation();
        $node->relation();
    }

    /**
     * @expectedException \MP\Cypher\QueryBuilderException
     */
    public function testRelationException()
    {
        $relation = new Relation();
        $relation->node();
        $relation->node();
    }

    /**
     * @expectedException \MP\Cypher\QueryBuilderException
     */
    public function testRelationRangeException()
    {
        $relation = new Relation();
        $relation->setRange('something bad');
    }
}
