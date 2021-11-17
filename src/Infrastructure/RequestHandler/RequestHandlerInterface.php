<?php

namespace App\Infrastructure\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface UserRequestHandlerInterface
 * @package App\Infrastructure\RequestHandler
 */
interface RequestHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int                                       $type
     */
    public function handle(Request $request, int $type): void;
}
