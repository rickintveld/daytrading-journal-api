<?php

namespace App\Domain\Contracts\Statistic;

use App\Domain\Statistic\StatisticResult;

interface Statistic
{
    public function getResult(): StatisticResult;
}
