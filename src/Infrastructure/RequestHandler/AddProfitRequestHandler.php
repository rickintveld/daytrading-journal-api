<?php

namespace App\Infrastructure\RequestHandler;

use App\Application\Command\AddProfitCommand;
use App\Common\Interfaces\Command;

/**
 * @package App\Infrastructure\RequestHandler
 */
class AddProfitRequestHandler extends RequestHandler
{
    /**
     * @param array $payload
     * @throws \Exception
     */
    protected function validatePayload(array $payload): void
    {
        foreach (['userId', 'profit'] as $key) {
            if (!array_key_exists($key, $payload)) {
                throw new \Exception(sprintf('No %s was found in the payload', $key));
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
        return new AddProfitCommand($payload['userId'], $payload['profit']);
    }
}
