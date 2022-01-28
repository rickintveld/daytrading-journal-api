<?php

namespace App\Common\Contracts;

/**
 * @package App\Common\Interfaces
 */
interface QueryBus
{
    /**
     * @param \App\Common\Contracts\Query $query
     * @return mixed
     */
    public function query(Query $query);
}
