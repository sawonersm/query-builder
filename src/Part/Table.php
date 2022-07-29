<?php

namespace QueryBuilder\Part;

class Table implements \Stringable
{
    private function __construct(
        private string $table,
        private ?string $alias,
    ) {
    }

    public static function create(string $table, ?string $alias): self
    {
        return new self(
            table: $table,
            alias: $alias,
        );
    }

    public function __toString(): string
    {
        return $this->alias
            ? "$this->table $this->alias"
            : "$this->table"
        ;
    }
}