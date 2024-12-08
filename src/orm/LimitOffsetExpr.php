<?php

namespace app\orm;

use app\orm\contracts\SqlPartExpression;
use app\orm\exceptions\NotValidLimitParamsException;

class LimitOffsetExpr implements SqlPartExpression
{
    private ?int $limit = null;
    private ?int $offset = null;

    public function __construct(?int $limit = null, ?int $offset = null)
    {
        if ($limit < 0 || $offset < 0) {
            throw new NotValidLimitParamsException('LIMIT and OFFSET can be only more than zero');
        }

        if ($offset && !$limit) {
            throw new NotValidLimitParamsException('LIMIT is required if OFFSET is');
        }

        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function isEmpty(): bool
    {
        return !$this->limit && !$this->offset;
    }

    public function getSql(): string
    {
        if (!$this->limit) {
            return '';
        }

        $offset = $this->offset ? " OFFSET {$this->offset}" : '';

        return "LIMIT {$this->limit}" . $offset;
    }
}
