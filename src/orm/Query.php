<?php

namespace app\orm;

use app\orm\exceptions\NotValidLimitParamsException;

class Query
{
    private string $from = '';
    private ?string $alias = null;

    private OrderByExpr $orderByExpr;
    private LimitOffsetExpr $limitOffsetExpr;

    public function __construct()
    {
        $this->orderByExpr = new OrderByExpr();
        $this->limitOffsetExpr = new LimitOffsetExpr();
    }

    /**
     * @var string[]
     */
    private array $fields = [];

    public function setFields(?array $fields = null): void
    {
        $this->fields = $fields ?? [];
    }

    public function orderBy(string $orderByField, ?string $direction = null): self
    {
        $this->orderByExpr = new OrderByExpr($orderByField, $direction);

        return $this;
    }

    public function select(?array $fields = null): self
    {
        $query = new $this();
        $query->setFields($fields);

        return $query;
    }

    public function from(string $table, ?string $alias = null): self
    {
        $this->from = $table;
        $this->alias = $alias;

        return $this;
    }

    public function first(): self
    {
        if (!$this->limitOffsetExpr->isEmpty()) {
            throw new NotValidLimitParamsException('command "->first" can not set if LIMIT was been used');
        }

        $this->limitOffsetExpr = new LimitOffsetExpr(1);

        return $this;
    }

    public function limit(int $limit, ?int $offset = null): self
    {
        if (!$this->limitOffsetExpr->isEmpty()) {
            throw new NotValidLimitParamsException('LIMIT can not set if command "->first" was been used');
        }

        $this->limitOffsetExpr = new LimitOffsetExpr($limit, $offset);

        return $this;
    }

    public function getSql(): string
    {
        $select = $this->getSelectSql();
        $from = $this->getFromSql();
        $limitOffset = $this->limitOffsetExpr->getSql();
        $orderSql = sprintf($this->orderByExpr->getSql(), $this->getTableAlias());

        return "SELECT {$select} FROM {$from}"
            . ($orderSql ? " {$orderSql}" : '')
            . ($limitOffset ? " {$limitOffset}" : '');
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

    private function getTableAlias(): string
    {
        return $this->alias ?? $this->from;
    }
}
