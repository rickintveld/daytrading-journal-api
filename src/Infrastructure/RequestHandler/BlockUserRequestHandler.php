<?php

namespace App\Infrastructure\RequestHandler;

use App\Application\Command\BlockUserCommand;
use App\Common\Interfaces\Command;
use \App\Common\Exception\InvalidArgumentException;

/**
 * @package App\Infrastructure\RequestHandler
 */
class BlockUserRequestHandler extends RequestHandler implements UserRequestHandlerInterface
{
    /**
     * @param int $requestType
     * @return bool
     */
    public function supports(int $requestType): bool
    {
        return RequestHandler::USER_BLOCK_TYPE === $requestType;
    }

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
        return new BlockUserCommand($payload['identifier']);
    }
}
