<?php

namespace App\Infrastructure\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Infrastructure\RequestHandler
 */
interface ProfitRequestHandlerInterface
{
    public function add(Request $request): void;

    public function withdraw(Request $request): void;
}
