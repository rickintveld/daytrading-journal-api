<?php

namespace App\Infrastructure\RequestHandler;

use App\Application\Command\CreateUserCommand;
use App\Common\Interfaces\Command;
use \App\Common\Exception\InvalidArgumentException;

/**
 * @package App\Infrastructure\RequestHandler
 */
class CreateUserRequestHandler extends RequestHandler
{
    /**
     * @param array $payload
     * @throws \Exception
     */
    protected function validatePayload(array $payload): void
    {
        foreach (['email', 'firstName', 'lastName', 'capital', 'password'] as $key) {
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
        return new CreateUserCommand(
            $payload['email'],
            $payload['firstName'],
            $payload['lastName'],
            $payload['capital'],
            $payload['password']
        );
    }
}
