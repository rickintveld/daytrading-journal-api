<?php

namespace App\Infrastructure\RequestHandler;

use App\Application\Command\UnBlockUserCommand;
use App\Common\Exception\InvalidArgumentException;
use App\Common\Interfaces\Command;

/**
 * @package App\Infrastructure\RequestHandler
 */
class UnblockUserRequestHandler extends RequestHandler
{
    /**
     * @param array<int> $payload
     * @throws \Exception
     */
    protected function validatePayload(array $payload): void
    {
        if (!array_key_exists('identifier', $payload)) {
            throw new InvalidArgumentException('No identifier was found in the payload');
        }
    }

    /**
     * @param array<int> $payload
     * @return \App\Common\Interfaces\Command
     * @throws \Exception
     */
    protected function createCommand(array $payload): Command
    {
        return new UnBlockUserCommand($payload['identifier']);
    }
}
