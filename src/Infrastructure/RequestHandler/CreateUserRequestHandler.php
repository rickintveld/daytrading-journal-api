<?php

namespace App\Infrastructure\RequestHandler;

use App\Application\Command\CreateUserCommand;
use App\Common\Contracts\Command;
use \App\Common\Exception\InvalidArgumentException;
use App\Infrastructure\Contracts\RequestHandler\UserRequestHandlerInterface;

/**
 * @package App\Infrastructure\RequestHandler
 */
class CreateUserRequestHandler extends RequestHandler implements UserRequestHandlerInterface
{
    /**
     * @param int $requestType
     * @return bool
     */
    public function supports(int $requestType): bool
    {
        return RequestHandler::USER_CREATE_TYPE === $requestType;
    }

    /**
     * @param array{email: string, firstName: string, lastName: string, capital: float, password: string} $payload
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
     * @param array{email: string, firstName: string, lastName: string, capital: int, password: string} $payload
     * @return \App\Common\Contracts\Command
     * @throws \Exception
     */
    protected function createCommand(array $payload): Command
    {
        return new CreateUserCommand(
            $payload['email'],
            $payload['firstName'],
            $payload['lastName'],
            (int)$payload['capital'],
            $payload['password']
        );
    }
}
