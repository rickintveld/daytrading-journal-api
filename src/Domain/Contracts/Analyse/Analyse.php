<?php

namespace App\Domain\Contracts\Analyse;

use App\Domain\Statistic\StatisticResult;

interface Analyse
{
    /**
     * @param \App\Domain\Contracts\Analyse\Analysable $analysable
     *
     * @return $this
     */
    public function set(Analysable $analysable): self;

    /**
     * @return \App\Domain\Statistic\StatisticResult
     */
    public function analyse(): StatisticResult;
}
