<?php

namespace App\Infrastructure\Builder;

/**
 * @package App\Infrastructure\Builder
 */
interface Builder
{
    public function build(array $arguments): void;
    public function get();
}
