<?php

namespace App\Common\Interfaces;

/**
 * @package App\Common\Interfaces
 */
interface QueryBus
{
    /**
     * @param \App\Common\Interfaces\Query $query
     * @return mixed
     */
    public function query(Query $query);
}
