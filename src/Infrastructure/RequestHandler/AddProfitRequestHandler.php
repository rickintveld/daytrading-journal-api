<?php

namespace App\Infrastructure\RequestHandler;

use App\Application\Command\AddProfitCommand;
use App\Common\Contracts\Command;
use App\Common\Exception\InvalidArgumentException;
use App\Infrastructure\Contracts\RequestHandler\ProfitRequestHandlerInterface;

class AddProfitRequestHandler extends RequestHandler implements ProfitRequestHandlerInterface
{
    public function supports(int $requestType): bool
    {
        return RequestHandler::PROFIT_ADD_TYPE === $requestType;
    }

    /**
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

    protected function createCommand(array $payload): Command
    {
        return new AddProfitCommand((int)$payload['userId'], (float)$payload['profit']);
    }
}
