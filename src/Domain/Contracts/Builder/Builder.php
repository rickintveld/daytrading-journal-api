<?php

namespace App\Domain\Contracts\Builder;

interface Builder
{
    public function build(array $arguments): mixed;
}
