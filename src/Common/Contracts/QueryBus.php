<?php

namespace App\Common\Contracts;

interface QueryBus
{
    /**
     * @param \App\Common\Contracts\Query $query
     * @return mixed
     */
    public function query(Query $query);
}
