<?php

namespace QueryBuilder\Part;

use QueryBuilder\Enum\JoinType;

class Join implements \Stringable
{
    public function __construct(
        private Table $table,
        private string $expression,
        private JoinType $type,
    ) {
    }

    public static function create(Table $table, string $expression, JoinType $type): self
    {
        return new self(
            table: $table,
            expression: $expression,
            type: $type,
        );
    }

    public function __toString(): string
    {
        switch ($this->type) {
            case JoinType::Inner:
                return "join $this->table on ($this->expression)";
            case JoinType::LeftJoin:
                return "left join $this->table on ($this->expression)";
            default:
                throw new \Exception("Not supported join type");
        }
    }
}