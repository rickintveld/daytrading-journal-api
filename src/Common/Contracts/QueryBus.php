<?php

namespace App\Common\Contracts;

interface QueryBus
{
    public function query(Query $query): mixed;
}
