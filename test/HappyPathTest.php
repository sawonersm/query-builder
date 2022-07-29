<?php

declare(strict_types=1);

namespace Test;

use PHPUnit\Framework\TestCase;
use QueryBuilder\QueryBuilder;

class HappyPathTest extends TestCase
{
    public function testOneSelect(): void
    {
        $qb = QueryBuilder::create();

        $qb
            ->select(["user.name"])
            ->from("users")
        ;

        $expected = "select user.name from users";
        self::assertEquals($expected, $qb->getQuery());
    }

    public function testSelectAlias(): void
    {
        $qb = QueryBuilder::create();

        $qb
            ->select(["user.name as userName"])
            ->from("users")
        ;

        $expected = "select user.name as userName from users";
        self::assertEquals($expected, $qb->getQuery());
    }

    public function testFrom(): void
    {
        $qb = QueryBuilder::create();

        $qb
            ->select(["user.name"])
            ->from("users")
        ;

        $expected = "select user.name from users";
        self::assertEquals($expected, $qb->getQuery());
    }

    public function testFromAlias(): void
    {
        $qb = QueryBuilder::create();

        $qb
            ->select(["user.name"])
            ->from("users", "user")
        ;

        $expected = "select user.name from users user";
        self::assertEquals($expected, $qb->getQuery());
    }

    public function testJoin(): void
    {
        $qb = QueryBuilder::create();

        $qb
            ->select(["user.name"])
            ->from("users", "user")
            ->join(
                "departments",
            "department",
            "user.department_id = department.id"
            )
        ;

        $expected = "select user.name from users user join departments department on (user.department_id = department.id)";
        self::assertEquals($expected, $qb->getQuery());
    }

    public function testLeftJoin(): void
    {
        $qb = QueryBuilder::create();

        $qb
            ->select(["user.name"])
            ->from("users", "user")
            ->leftJoin(
                "departments",
                "department",
                "user.department_id = department.id"
            )
        ;

        $expected = "select user.name from users user left join departments department on (user.department_id = department.id)";
        self::assertEquals($expected, $qb->getQuery());
    }

    public function testLimit(): void
    {
        $qb = QueryBuilder::create();

        $qb
            ->select(["user.name"])
            ->from("users")
            ->limit(10)
        ;

        $expected = "select user.name from users limit 10";
        self::assertEquals($expected, $qb->getQuery());
    }

    public function testOffset(): void
    {
        $qb = QueryBuilder::create();

        $qb
            ->select(["user.name"])
            ->from("users")
            ->limit(10)
            ->offset(100)
        ;

        $expected = "select user.name from users limit 10 offset 100";
        self::assertEquals($expected, $qb->getQuery());
    }

    public function testOneAndWhere(): void
    {
        $qb = QueryBuilder::create();

        $qb->select([
            "user.id",
        ]);

        $qb->from("users", "user");
        $qb->andWhere("user.id = :userId");

        $expected = "select user.id from users user where user.id = :userId";
        self::assertEquals($expected, $qb->getQuery());
    }

    public function testMultipleWhere(): void
    {
        $qb = QueryBuilder::create();

        $qb
            ->select(["user.id",])
            ->from("users", "user")
            ->andWhere("user.id = :userId")
            ->andWhere("user.name = :userName")
            ->andWhere("user.phone = :userPhone")
        ;

        $expected = "select user.id from users user where user.id = :userId and user.name = :userName and user.phone = :userPhone";
        self::assertEquals($expected, $qb->getQuery());
    }
}