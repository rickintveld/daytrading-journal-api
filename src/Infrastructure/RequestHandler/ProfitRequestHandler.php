<?php

namespace App\Infrastructure\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Infrastructure\RequestHandler
 */
class ProfitRequestHandler implements ProfitRequestHandlerInterface
{
    /** @var iterable<ProfitRequestHandlerInterface> */
    protected iterable $profitRequestHandlers;

    /**
     * @param iterable<ProfitRequestHandlerInterface> $userRequestHandlers
     */
    public function __construct(iterable $userRequestHandlers)
    {
        foreach ($userRequestHandlers as $handler) {
            $this->addHandler($handler);
        }
    }

    /**
     * @param \App\Infrastructure\RequestHandler\ProfitRequestHandlerInterface $handler
     */
    public function addHandler(ProfitRequestHandlerInterface $handler): void
    {
        $this->handlers[get_class($handler)] = $handler;
    }

    /**
     * @param int                                       $type
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function handle(Request $request, int $type): void
    {
        foreach ($this->profitRequestHandlers as $handler) {
            if ($handler->supports($type)) {
                $handler->handle($request);
            }
        }
    }
}
