<?php

namespace QueryBuilder\Part;

class Column implements \Stringable
{
    private function __construct(
        private string $column,
    ) {
    }

    public static function create(string $column): self
    {
        return new self($column);
    }

    public function __toString(): string
    {
        return $this->column;
    }
}