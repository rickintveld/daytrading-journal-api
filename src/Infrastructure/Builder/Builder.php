<?php

namespace App\Infrastructure\Builder;

/**
 * @package App\Infrastructure\Builder
 */
interface Builder
{
    /**
     * @param array<mixed> $arguments
     * @return mixed
     */
    public function build(array $arguments);
}
