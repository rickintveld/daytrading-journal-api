<?php

namespace App\Domain\Contracts\Builder;

/**
 * @package App\Domain\Builder
 */
interface Builder
{
    /**
     * @param array $arguments
     * @return mixed
     */
    public function build(array $arguments);
}
