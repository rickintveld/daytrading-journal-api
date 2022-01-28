<?php

namespace App\Infrastructure\RequestHandler;

use App\Infrastructure\Contracts\RequestHandler\UserRequestHandlerInterface;
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
     * @param \App\Infrastructure\Contracts\RequestHandler\UserRequestHandlerInterface $handler
     * @return void
     */
    public function addHandler(UserRequestHandlerInterface $handler): void
    {
        $this->userRequestHandlers[get_class($handler)] = $handler;
    }

    /**
     * @param int                                       $type
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function handle(Request $request, int $type): void
    {
        /** @var UserRequestHandlerInterface $handler */
        foreach ($this->userRequestHandlers as $handler) {
            if ($handler->supports($type)) {
                $handler->handle($request);
            }
        }
    }
}
