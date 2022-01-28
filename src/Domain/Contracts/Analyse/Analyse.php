<?php

namespace App\Domain\Contracts\Analyse;

use App\Domain\Statistic\StatisticResult;

/**
 * @package App\Domain\Analyse
 */
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
