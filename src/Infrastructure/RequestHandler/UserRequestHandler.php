<?php

namespace App\Infrastructure\RequestHandler;

use App\Infrastructure\Contracts\RequestHandler\UserRequestHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

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

    public function addHandler(UserRequestHandlerInterface $handler): void
    {
        $this->userRequestHandlers[get_class($handler)] = $handler;
    }

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
