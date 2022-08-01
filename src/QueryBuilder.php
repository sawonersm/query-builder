<?php

namespace QueryBuilder;

use QueryBuilder\Enum\ClauseType;
use QueryBuilder\Enum\JoinType;
use QueryBuilder\Part\Clause;
use QueryBuilder\Part\Join;
use QueryBuilder\Part\Table;

class QueryBuilder
{
    private function __construct(
        private array $columns = [],
        private ?Table $from = null,
        private array $joins = [],
        private ?int $limit = null,
        private ?int $offset = null,
        private array $clauses = [],
    ) {
    }

    public static function create(): self
    {
        return new self();
    }

    public function select(array $columns): self
    {
        $this->columns = [];
        foreach ($columns as $column) {
            $this->columns[] = $column;
        }

        return $this;
    }

    public function from(string $from, ?string $alias = null): self
    {
        $this->from = Table::create($from, $alias);

        return $this;
    }

    public function join(string $table, ?string $alias, string $expression): self
    {
        $this->joins[] = Join::create(
            Table::create($table, $alias),
            $expression,
            JoinType::Inner,
        );

        return $this;
    }

    public function leftJoin(string $table, ?string $alias, string $expression): self
    {
        $this->joins[] = Join::create(
            Table::create($table, $alias),
            $expression,
            JoinType::LeftJoin,
        );

        return $this;
    }

    public function andWhere(string $clause): self
    {
        $this->clauses[] = Clause::create($clause, ClauseType::And);
        return $this;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function getQuery(): string
    {
        $columns = \array_map(fn($column) => (string) $column, $this->columns);
        $columns = implode(", ", $columns);

        $from = (string) $this->from;

        $query = "select $columns from $from";
        if (!empty($this->joins)) {
            $joins = \array_map(fn($join) => (string) $join, $this->joins);
            $joins = implode(" ", $joins);
            $query .= " $joins";
        }

        if (!empty($this->clauses)) {
            $clauses = \array_values(\array_map(fn($clause) => (string) $clause, $this->clauses));
            $clauses[0] = preg_replace('/(and|or)\s/', '', $clauses[0], 1);
            $clauses = implode(' ', $clauses);
            $query .= " where $clauses";
        }

        if ($this->limit) {
            $query .= " limit $this->limit";
        }

        if ($this->offset) {
            $query .= " offset $this->offset";
        }

        return $query;
    }
}