<?php

namespace App\Infrastructure\RequestHandler;

use App\Infrastructure\Contracts\RequestHandler\ProfitRequestHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProfitRequestHandler
{
    /** @var iterable<ProfitRequestHandlerInterface> */
    protected iterable $profitRequestHandlers;

    /**
     * @param iterable<ProfitRequestHandlerInterface> $profitRequestHandlers
     */
    public function __construct(iterable $profitRequestHandlers)
    {
        foreach ($profitRequestHandlers as $handler) {
            $this->addHandler($handler);
        }
    }

    public function addHandler(ProfitRequestHandlerInterface $handler): void
    {
        $this->profitRequestHandlers[get_class($handler)] = $handler;
    }

    public function handle(Request $request, int $type): void
    {
        /** @var ProfitRequestHandlerInterface $handler */
        foreach ($this->profitRequestHandlers as $handler) {
            if ($handler->supports($type)) {
                $handler->handle($request);
            }
        }
    }
}
