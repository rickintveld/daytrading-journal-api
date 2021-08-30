<?php

namespace App\Common\Bus;

use App\Common\Interfaces\Query;
use App\Common\Interfaces\QueryBus;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @package App\Common\Bus
 */
final class MessengerQueryBus implements QueryBus
{
    use HandleTrait;

    /**
     * @param \Symfony\Component\Messenger\MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @param \App\Common\Interfaces\Query $query
     * @return mixed|null
     */
    public function query(Query $query)
    {
        return $this->handle($query);
    }
}
