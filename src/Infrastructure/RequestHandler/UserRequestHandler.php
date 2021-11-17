<?php

namespace App\Infrastructure\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Infrastructure\RequestHandler
 */
class UserRequestHandler
{
    /** @var iterable<UserRequestHandlerInterface> */
    protected iterable $userRequestHandlers;

    /**
     * @param iterable<UserRequestHandlerInterface> $userRequestHandlers
     */
    public function __construct(iterable $userRequestHandlers)
    {
        foreach ($userRequestHandlers as $handler) {
            $this->addHandler($handler);
        }
    }

    /**
     * @param \App\Infrastructure\RequestHandler\UserRequestHandlerInterface $handler
     */
    public function addHandler(UserRequestHandlerInterface $handler): void
    {
        $this->handlers[get_class($handler)] = $handler;
    }

    /**
     * @param int                                       $type
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return null
     */
    public function handle(Request $request, int $type): void
    {
        foreach ($this->userRequestHandlers as $handler) {
            if ($handler->supports($type)) {
                $handler->handle($request);
            }
        }
    }
}
