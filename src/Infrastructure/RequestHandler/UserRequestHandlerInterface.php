<?php

namespace App\Infrastructure\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface UserRequestHandlerInterface
 * @package App\Infrastructure\RequestHandler
 */
interface UserRequestHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function create(Request $request): void;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function block(Request $request): void;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function unblock(Request $request): void;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function remove(Request $request): void;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function restore(Request $request): void;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function update(Request $request): void;
}
