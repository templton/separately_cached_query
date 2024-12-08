<?php

namespace app\orm\contracts;

interface SqlPartExpression
{
    public function getSql(): string;
}
