<?php

namespace App\Infrastructure\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Infrastructure\RequestHandler
 */
interface ProfitRequestHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function add(Request $request): void;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function withdraw(Request $request): void;
}
