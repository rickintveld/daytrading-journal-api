<?php

namespace App\Infrastructure\RequestHandler;

use App\Application\Command\WithdrawCommand;
use App\Common\Exception\InvalidArgumentException;
use App\Common\Interfaces\Command;

/**
 * @package App\Infrastructure\RequestHandler
 */
class WithdrawRequestHandler extends RequestHandler
{
    /**
     * @param array $payload
     * @throws \Exception
     */
    protected function validatePayload(array $payload): void
    {
        foreach (['userId', 'amount'] as $key) {
            if (!array_key_exists($key, $payload)) {
                throw new InvalidArgumentException(sprintf('No %s was found in the payload', $key));
            }
        }
    }

    /**
     * @param array $payload
     * @return \App\Common\Interfaces\Command
     * @throws \Exception
     */
    protected function createCommand(array $payload): Command
    {
        return new WithdrawCommand($payload['userId'], $payload['amount']);
    }
}
