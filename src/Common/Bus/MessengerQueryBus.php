<?php

namespace App\Common\Bus;

use App\Common\Contracts\Query;
use App\Common\Contracts\QueryBus;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerQueryBus implements QueryBus
{
    use HandleTrait;

    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function query(Query $query): mixed
    {
        return $this->handle($query);
    }
}
