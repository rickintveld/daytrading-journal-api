<?php

namespace App\Infrastructure\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Infrastructure\RequestHandler
 */
class ProfitRequestHandler implements ProfitRequestHandlerInterface
{
    /** @var \App\Infrastructure\RequestHandler\AddProfitRequestHandler */
    private $addProfitRequestHandler;

    /** @var \App\Infrastructure\RequestHandler\WithdrawRequestHandler */
    private $withdrawRequestHandler;

    /**
     * @param \App\Infrastructure\RequestHandler\AddProfitRequestHandler $addProfitRequestHandler
     * @param \App\Infrastructure\RequestHandler\WithdrawRequestHandler  $withdrawRequestHandler
     */
    public function __construct(
        AddProfitRequestHandler $addProfitRequestHandler,
        WithdrawRequestHandler $withdrawRequestHandler
    ) {
        $this->addProfitRequestHandler = $addProfitRequestHandler;
        $this->withdrawRequestHandler = $withdrawRequestHandler;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function add(Request $request): void
    {
        $this->addProfitRequestHandler->handle($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function withdraw(Request $request): void
    {
        $this->withdrawRequestHandler->handle($request);
    }
}
