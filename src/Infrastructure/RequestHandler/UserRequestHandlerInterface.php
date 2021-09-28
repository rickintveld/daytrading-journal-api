<?php

namespace App\Infrastructure\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface UserRequestHandlerInterface
 * @package App\Infrastructure\RequestHandler
 */
interface UserRequestHandlerInterface
{
    public function create(Request $request): void;

    public function block(Request $request): void;

    public function unblock(Request $request): void;

    public function remove(Request $request): void;

    public function restore(Request $request): void;

    public function update(Request $request): void;
}
