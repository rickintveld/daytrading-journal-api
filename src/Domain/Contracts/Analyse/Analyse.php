<?php

namespace App\Domain\Contracts\Analyse;

use App\Domain\Statistic\StatisticResult;

interface Analyse
{
    public function set(Analysable $analysable): self;

    public function analyse(): StatisticResult;
}
