<?php

namespace app\orm;

use app\orm\contracts\SqlPartExpression;
use app\orm\exceptions\NotValidOrderDirection;

class OrderByExpr implements SqlPartExpression
{
    public const ASC = 'ASC';
    public const DESC = 'DESC';

    public const DEFAULT_VALUE = self::DESC;

    private string $orderDirection;
    private ?string $orderByField;

    /**
     * @param string|null $orderDirection
     * @param string|null $orderByField
     * @throws NotValidOrderDirection
     */
    public function __construct(?string $orderByField = null, ?string $orderDirection = null)
    {
        $this->orderDirection = $this->prepareOrderDirection($orderDirection);
        $this->orderByField = $orderByField;
    }

    public function getSql(): string
    {
        if (!$this->orderByField) {
            return '';
        }

        return "ORDER BY %s.{$this->orderByField} {$this->orderDirection}";
    }

    private function prepareOrderDirection(?string $orderDirection): string
    {
        if (!$orderDirection) {
            return self::DEFAULT_VALUE;
        }

        $orderDirection = strtoupper($orderDirection);

        if (!in_array($orderDirection, [self::ASC, self::DESC])) {
            throw new NotValidOrderDirection(
                "Wrong order direction - $orderDirection. Can be only " . join(', ', [self::ASC, self::DESC])
            );
        }

        return $orderDirection === self::ASC ? self::ASC : self::DESC;
    }
}
