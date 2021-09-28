<?php

namespace App\Infrastructure\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Infrastructure\RequestHandler
 */
class UserRequestHandler implements UserRequestHandlerInterface
{
    /** @var \App\Infrastructure\RequestHandler\CreateUserRequestHandler */
    private $createUserRequestHandler;

    /** @var \App\Infrastructure\RequestHandler\BlockUserRequestHandler */
    private $blockUserRequestHandler;

    /** @var \App\Infrastructure\RequestHandler\UnblockUserRequestHandler */
    private $unblockUserRequestHandler;

    /** @var \App\Infrastructure\RequestHandler\RemoveUserRequestHandler */
    private $removeUserRequestHandler;

    /** @var \App\Infrastructure\RequestHandler\RestoreUserRequestHandler */
    private $restoreUserRequestHandler;

    /** @var \App\Infrastructure\RequestHandler\UpdateUserRequestHandler */
    private $updateUserRequestHandler;

    /**
     * @param \App\Infrastructure\RequestHandler\CreateUserRequestHandler  $createUserRequestHandler
     * @param \App\Infrastructure\RequestHandler\BlockUserRequestHandler   $blockUserRequestHandler
     * @param \App\Infrastructure\RequestHandler\UnblockUserRequestHandler $unblockUserRequestHandler
     * @param \App\Infrastructure\RequestHandler\RemoveUserRequestHandler  $removeUserRequestHandler
     * @param \App\Infrastructure\RequestHandler\RestoreUserRequestHandler $restoreUserRequestHandler
     * @param \App\Infrastructure\RequestHandler\UpdateUserRequestHandler  $updateUserRequestHandler
     */
    public function __construct(
        CreateUserRequestHandler $createUserRequestHandler,
        BlockUserRequestHandler $blockUserRequestHandler,
        UnblockUserRequestHandler $unblockUserRequestHandler,
        RemoveUserRequestHandler $removeUserRequestHandler,
        RestoreUserRequestHandler $restoreUserRequestHandler,
        UpdateUserRequestHandler $updateUserRequestHandler
    ) {
        $this->createUserRequestHandler = $createUserRequestHandler;
        $this->blockUserRequestHandler = $blockUserRequestHandler;
        $this->unblockUserRequestHandler = $unblockUserRequestHandler;
        $this->removeUserRequestHandler = $removeUserRequestHandler;
        $this->restoreUserRequestHandler = $restoreUserRequestHandler;
        $this->updateUserRequestHandler = $updateUserRequestHandler;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function create(Request $request): void
    {
        $this->createUserRequestHandler->handle($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function block(Request $request): void
    {
        $this->blockUserRequestHandler->handle($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function unblock(Request $request): void
    {
        $this->unblockUserRequestHandler->handle($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function remove(Request $request): void
    {
        $this->removeUserRequestHandler->handle($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function restore(Request $request): void
    {
        $this->restoreUserRequestHandler->handle($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function update(Request $request): void
    {
        $this->updateUserRequestHandler->handle($request);
    }
}
