<?php

use app\orm\exceptions\NotValidLimitParamsException;
use app\orm\exceptions\NotValidOrderDirection;
use app\orm\Query;

/**
 * + С алиасом таблицы и без
 * + Все записи или первую/последнюю
 * + limit/offset
 * + limit/offset + first() - должно учитываться только что-то одно
 * + Сортировка
 */

class QueryFromOneTableWithoutParamsTest extends \Codeception\Test\Unit
{
    private Query $query;

    public function setUp(): void
    {
        parent::setUp();
        $this->query = new Query();
    }

    public function testSimpleAll()
    {
        // Все поля без алиаса таблицы
        $expected = 'SELECT student.* FROM student';
        $this->assertSame($expected, $this->createQuery('student'));

        // Все поля без алиаса таблицы
        $expected = 'SELECT teacher.* FROM teacher';
        $this->assertSame($expected, $this->createQuery('teacher'));

        // Все поля + алиас таблицы
        $expected = 'SELECT t.* FROM teacher t';
        $this->assertSame($expected, $this->createQuery('teacher', 't'));

        // Конкретные поля с алиасом таблицы
        $expected = 'SELECT student.id, student.grade_id FROM student';
        $this->assertSame($expected, $this->createQuery('student', null, ['id', 'grade_id']));

        // Конкретные поля без алиаса таблицы
        $expected = 'SELECT stud.id, stud.grade_id FROM student stud';
        $this->assertSame($expected, $this->createQuery('student', 'stud', ['id', 'grade_id']));
    }

    public function testCountExpression()
    {
        $expected = 'SELECT student.* FROM student LIMIT 1';
        $this->assertSame($expected, $this->createQuery('student', null, null, 'first'));
    }

    public function testPagination()
    {
        $expected = 'SELECT student.* FROM student LIMIT 15 OFFSET 100';
        $actual = $this->createQuery('student', null, null, null, 15, 100);
        $this->assertSame($expected, $actual);

        $expected = 'SELECT student.* FROM student LIMIT 17';
        $actual = $this->createQuery('student', null, null, null, 17);
        $this->assertSame($expected, $actual);

        $expected = 'SELECT student.* FROM student LIMIT 19';
        $actual = $this->createQuery('student', null, null, null, 19);
        $this->assertSame($expected, $actual);
    }

    public function testException_FirstAndLimitTogether1()
    {
        $this->expectException(NotValidLimitParamsException::class);
        $this->query->select()->from('student')->limit(100)->first();
    }

    public function testException_FirstAndLimitTogether2()
    {
        $this->expectException(NotValidLimitParamsException::class);
        $this->query->select()->from('student')->first()->limit(100);
    }

    public function testException_wrongOrderDirection()
    {
        $this->expectException(NotValidOrderDirection::class);
        $this->createQuery('student', null, null, null, null, null, ['name', 'ass']);
    }

    public function testQueryOrderAndLimit()
    {
        // без алиаса
        $expected = 'SELECT student.* FROM student ORDER BY student.name DESC';
        $actual = $this->createQuery('student', null, null, null, null, null, ['name', 'desc']);
        $this->assertSame($expected, $actual);

        // с алиасом
        // order desc
        $expected = 'SELECT t.* FROM student t ORDER BY t.name DESC';
        $actual = $this->createQuery('student', 't', null, null, null, null, ['name', 'desc']);
        $this->assertSame($expected, $actual);

        // order asc
        $expected = 'SELECT t.* FROM student t ORDER BY t.name ASC';
        $actual = $this->createQuery('student', 't', null, null, null, null, ['name', 'asc']);
        $this->assertSame($expected, $actual);

        // order default direction
        // default order
        $expected = 'SELECT t.* FROM student t ORDER BY t.name DESC';
        $actual = $this->createQuery('student', 't', null, null, null, null, ['name']);
        $this->assertSame($expected, $actual);

        // limit + offset + order
        $expected = 'SELECT t.* FROM student t ORDER BY t.name ASC LIMIT 10 OFFSET 100';
        $actual = $this->createQuery('student', 't', null, null, 10, 100, ['name', 'asc']);
        $this->assertSame($expected, $actual);

        // limit + order
        $expected = 'SELECT t.* FROM student t ORDER BY t.name ASC LIMIT 10';
        $actual = $this->createQuery('student', 't', null, null, 10, null, ['name', 'asc']);
        $this->assertSame($expected, $actual);

        // order + first
        $expected = 'SELECT t.* FROM student t ORDER BY t.name ASC LIMIT 1';
        $actual = $this->createQuery('student', 't', null, 'first', null, null, ['name', 'asc']);
        $this->assertSame($expected, $actual);
    }

    public function testFullExpression()
    {
        $expected = 'SELECT s.id, s.name FROM student s ORDER BY s.name DESC LIMIT 15 OFFSET 110';
        $actual = $this->query->select(['id', 'name'])->from('student', 's')->orderBy('name')->limit(15, 110)->getSql();
        $this->assertSame($expected, $actual);
    }

    private function createQuery(
        string $tableName,
        ?string $tableAlias = null,
        ?array $fields = null,
        ?string $countExp = 'all',
        ?int $limit = null,
        ?int $offset = null,
        ?array $order = null
    ): string {
        $query = $this->query->select($fields)->from($tableName, $tableAlias);

        if ($order) {
            $query->orderBy($order[0], $order[1] ?? null);
        }

        switch ($countExp) {
            case 'first':
                $query->first();
                break;
        }

        if ($limit || $offset) {
            $query->limit($limit, $offset);
        }

        return $query->getSql();
    }
}
