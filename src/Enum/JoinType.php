<?php

declare(strict_types=1);

namespace QueryBuilder\Enum;

enum JoinType: string
{
    case Inner = "inner";
    case LeftJoin = "left join";
}