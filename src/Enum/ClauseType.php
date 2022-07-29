<?php

declare(strict_types=1);

namespace QueryBuilder\Enum;

enum ClauseType: string
{
    case And = "and";
    case Or = "or";
}