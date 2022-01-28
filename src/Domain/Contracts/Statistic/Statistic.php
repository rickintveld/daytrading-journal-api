<?php

namespace App\Domain\Contracts\Statistic;

use App\Domain\Statistic\StatisticResult;

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
