# PHP Cypher QueryBuilder

A php query builder for cypher queries.

*WORK IN PROGRESS*

## Installation

    $ composer require maxperrimond/cypher-querybuilder

## Usage

```php
<?php

use MP\Cypher\QueryBuilder;

$qb = new QueryBuilder();

$qb->addMatch()
    ->node('u', 'user')
    ->relation()
    ->setWay(\MP\Cypher\Query\Relation::RIGHT)
    ->node('n');
$qb->where()->expr("u.name = 'foo'");
$qb->skip(10);
$qb->limit(10);

echo $qb->getQuery('u', 'n');
// MATCH (u:user)-[]->(n) WHERE u.name = 'foo' RETURN u,n SKIP 10 LIMIT 10
```
