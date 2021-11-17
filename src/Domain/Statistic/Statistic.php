<?php

namespace App\Domain\Statistic;

/**
 * @package App\Domain\Statistic
 */
interface Statistic
{
    /**
     * @return \App\Domain\Statistic\StatisticResult
     */
    public function getResult(): StatisticResult;
}
