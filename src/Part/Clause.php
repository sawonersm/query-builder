<?php

namespace QueryBuilder\Part;

use QueryBuilder\Enum\ClauseType;

class Clause implements \Stringable
{
    private function __construct(
        private string $clause,
        private ClauseType $type,
    ) {
    }

    public static function create(string $clause, ClauseType $type): self
    {
        return new self($clause, $type);
    }

    public function __toString(): string
    {
        return "{$this->type->value} $this->clause";
    }
}