<?php

namespace app\orm;

use app\orm\exceptions\NotValidLimitParamsException;
use app\orm\exceptions\NotValidOrderDirection;
use yii\db\Exception;

class Query
{
    private string $from = '';
    private ?string $alias = null;
    private ?string $countExpression = null;
    private int $limit = 0;
    private int $offset = 0;

    private ?string $orderByField = null;
    private string $orderDirection = 'DESC';

    /**
     * @var string[]
     */
    private array $fields = [];

    public function setFields(?array $fields = null): void
    {
        $this->fields = $fields ?? [];
    }

    public function orderBy(string $orderByName, ?string $direction): self
    {
        $this->orderByField = $orderByName;

        if (!$direction) {
            return $this;
        }

        $direction = strtoupper($direction);

        if (!in_array($direction, ['ASC', 'DESC'])) {
            throw new NotValidOrderDirection("Wrong order direction - $direction. Can be only DESC, ASC");
        }

        $this->orderDirection = $direction === 'ASC' ? 'ASC' : 'DESC';

        return $this;
    }

    public function select(?array $fields = null)
    {
        $query = new $this();
        $query->setFields($fields);

        return $query;
    }

    public function from(string $table, ?string $aliase = null)
    {
        $this->from = $table;
        $this->alias = $aliase;

        return $this;
    }

    public function first()
    {
        if ($this->limit) {
            $this->throwFirstLimitException();
        }

        $this->countExpression = 'first';
        return $this;
    }

    public function limit(int $limit, ?int $offset = null)
    {
        if ($this->countExpression === 'first') {
            $this->throwFirstLimitException();
        }

        $this->limit = $limit;
        $this->offset = $offset ?? 0;

        return $this;
    }

    private function getSelectSql(): string
    {
        $tableAlias = $this->getTableAlias();

        if (!count($this->fields)) {
            return "{$tableAlias}.*";
        }

        $fieldsWithAlias = array_map(fn(string $field) => "{$tableAlias}.{$field}", $this->fields);

        return join(', ', $fieldsWithAlias);
    }

    private function getFromSql(): string
    {
        return $this->alias ? "{$this->from} {$this->alias}" : $this->from;
    }

    public function getSql(): string
    {
        $select = $this->getSelectSql();
        $from = $this->getFromSql();
        $countSql = $this->getCountSql();
        $pagination = $this->getPagination();
        $direction = $this->getOrderSql();

        return "SELECT {$select} FROM {$from}"
            . ($countSql ? " {$countSql}" : '')
            . ($pagination ? " {$pagination}" : '')
            . ($direction ? " {$direction}" : '');
    }

    private function getTableAlias(): string
    {
        return $this->alias ?? $this->from;
    }

    private function getCountSql(): string
    {
        if (!$this->countExpression) {
            return '';
        }

        return 'LIMIT 1';
    }

    private function getPagination(): string
    {
        if (!$this->limit) {
            return '';
        }

        return "LIMIT {$this->limit}" . ($this->offset ? " OFFSET {$this->offset}" : '');
    }

    private function getOrderSql(): string
    {
        if (!$this->orderByField) {
            return '';
        }

        $alias = $this->getTableAlias();

        return "ORDER BY {$alias}.{$this->orderByField} {$this->orderDirection}";
    }

    private function throwFirstLimitException(): void
    {
        throw new NotValidLimitParamsException('FIRST and LIMIT can be applied together');
    }
}
