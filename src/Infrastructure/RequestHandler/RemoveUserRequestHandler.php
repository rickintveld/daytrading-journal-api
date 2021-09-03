<?php

namespace App\Infrastructure\RequestHandler;

use App\Application\Command\RemoveUserCommand;
use App\Common\Exception\InvalidUserStateException;
use App\Common\Interfaces\Command;

/**
 * @package App\Infrastructure\RequestHandler
 */
class RemoveUserRequestHandler extends RequestHandler
{
    /**
     * @param array $payload
     * @throws \Exception
     */
    protected function validatePayload(array $payload): void
    {
        if (!array_key_exists('identifier', $payload)) {
            throw new InvalidUserStateException('No identifier was found in the payload');
        }
    }

    /**
     * @param array $payload
     * @return \App\Common\Interfaces\Command
     * @throws \Exception
     */
    protected function createCommand(array $payload): Command
    {
        return new RemoveUserCommand($payload['identifier']);
    }
}
