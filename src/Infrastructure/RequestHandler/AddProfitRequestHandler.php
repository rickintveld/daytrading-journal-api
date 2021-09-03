<?php

namespace App\Infrastructure\RequestHandler;

use App\Application\Command\AddProfitCommand;
use App\Common\Interfaces\Command;
use \App\Common\Exception\InvalidArgumentException;

/**
 * @package App\Infrastructure\RequestHandler
 */
class AddProfitRequestHandler extends RequestHandler
{
    /**
     * @param array $payload
     * @throws InvalidArgumentException
     */
    protected function validatePayload(array $payload): void
    {
        foreach (['userId', 'profit'] as $key) {
            if (!array_key_exists($key, $payload)) {
                throw new InvalidArgumentException(sprintf('No %s was found in the payload', $key));
            }
        }
    }

    /**
     * @param array $payload
     * @return \App\Common\Interfaces\Command
     */
    protected function createCommand(array $payload): Command
    {
        return new AddProfitCommand($payload['userId'], $payload['profit']);
    }
}
