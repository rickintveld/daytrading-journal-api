<?php

namespace App\Domain\Statistic;

/**
 * @package App\Domain\Statistic
 */
interface Statistic
{
    public function getResult(): array;
}
