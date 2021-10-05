<?php

namespace App\Domain\Builder;

/**
 * @package App\Domain\Builder
 */
interface Builder
{
    /**
     * @param array<mixed> $arguments
     * @return mixed
     */
    public function build(array $arguments);
}
