<?php

namespace App\Infrastructure\RequestHandler;

use App\Application\Command\WithdrawCommand;
use App\Common\Exception\InvalidArgumentException;
use App\Common\Contracts\Command;
use App\Infrastructure\Contracts\RequestHandler\ProfitRequestHandlerInterface;

class WithdrawRequestHandler extends RequestHandler implements ProfitRequestHandlerInterface
{
    public function supports(int $requestType): bool
    {
        return RequestHandler::PROFIT_WITHDRAW_TYPE === $requestType;
    }

    /**
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
     * @throws \Exception
     */
    protected function createCommand(array $payload): Command
    {
        return new WithdrawCommand((int)$payload['userId'], (float)$payload['amount']);
    }
}
