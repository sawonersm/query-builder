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

    public function testSelectOverride(): void
    {
        $qb = QueryBuilder::create();

        $qb
            ->select(["user.name"])
            ->from("users")
            ->select(["user.id"])
        ;

        $expected = "select user.id from users";
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

    public function testAddOrderBy(): void
    {
        $qb = QueryBuilder::create();

        $qb
            ->select(["user.name"])
            ->from("users")
            ->addOrderBy("user.name desc")
        ;

        $expected = "select user.name from users order by user.name desc";
        self::assertEquals($expected, $qb->getQuery());
    }

    public function testOrderBy(): void
    {
        $qb = QueryBuilder::create();

        $qb
            ->select(["user.name"])
            ->from("users")
            ->orderBy([
                "user.name desc",
                "user.createdDate asc"
            ])
        ;

        $expected = "select user.name from users order by user.name desc,user.createdDate asc";
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

    public function testParameters(): void
    {
        $qb = QueryBuilder::create();
        $qb->setParameter("param1", "value1");
        $qb->setParameter("param2", "value2");

        $parameters = $qb->getParameters();
        self::assertArrayHasKey("param1", $parameters);
        self::assertArrayHasKey("param2", $parameters);
        self::assertEquals("value1", $parameters["param1"]);
        self::assertEquals("value2", $parameters["param2"]);
    }
}