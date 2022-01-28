<?php

namespace App\Domain\Contracts\Statistic;

use App\Domain\Statistic\StatisticResult;

interface Statistic
{
    /**
     * @return \App\Domain\Statistic\StatisticResult
     */
    public function getResult(): StatisticResult;
}
