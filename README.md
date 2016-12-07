# PHP Cypher QueryBuilder

[![Build Status](https://travis-ci.com/maxperrimond/cypher-querybuilder.svg?token=txabW3XnmBRNYuhMQNLm&branch=master)](https://travis-ci.com/maxperrimond/cypher-querybuilder)

A php query builder for cypher queries.

*WORK IN PROGRESS*

## Installation

    $ composer require mper/cypher-querybuilder

## Usage

```php
<?php

use MP\Cypher\QueryBuilder;

$qb = new QueryBuilder();

$qb->addMatch()
    ->addNode('u', 'user')
    ->relation()->right()
    ->node('n');
$qb->where()->expr("u.name = 'foo'");
$qb->skip(10);
$qb->limit(10);

echo $qb->getQuery('u', 'n');
// MATCH (u:user)-[]->(n) WHERE u.name = 'foo' RETURN u,n SKIP 10 LIMIT 10
```
