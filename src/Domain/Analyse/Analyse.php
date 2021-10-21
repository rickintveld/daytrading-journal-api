<?php

namespace App\Domain\Analyse;

/**
 * @package App\Domain\Analyse
 */
interface Analyse
{
    public function set(Analysable $analysable): self;

    public function analyse();
}
