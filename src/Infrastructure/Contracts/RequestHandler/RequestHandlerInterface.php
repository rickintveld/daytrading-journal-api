<?php

namespace App\Infrastructure\Contracts\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface UserRequestHandlerInterface
 * @package App\Infrastructure\RequestHandler
 */
interface RequestHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function handle(Request $request): void;

    /**
     * @param int $requestType
     * @return bool
     */
    public function supports(int $requestType): bool;
}
