<?php

namespace App\Infrastructure\Controller;

use App\Application\Query\AllUsersQuery;
use App\Application\Query\FindUserQuery;
use App\Common\Interfaces\QueryBus;
use App\Infrastructure\RequestHandler\BlockUserRequestHandler;
use App\Infrastructure\RequestHandler\CreateUserRequestHandler;
use App\Infrastructure\RequestHandler\RemoveUserRequestHandler;
use App\Infrastructure\RequestHandler\RestoreUserRequestHandler;
use App\Infrastructure\RequestHandler\UnblockUserRequestHandler;
use App\Infrastructure\RequestHandler\UpdateUserRequestHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Infrastructure\Controller
 */
class UserController extends AbstractController
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

    /** @var \App\Common\Bus\MessengerQueryBus */
    private $queryBus;

    /** @var \Symfony\Component\Serializer\SerializerInterface */
    private $serializer;

    /**
     * @param \App\Infrastructure\RequestHandler\CreateUserRequestHandler  $createUserRequestHandler
     * @param \App\Infrastructure\RequestHandler\BlockUserRequestHandler   $blockUserRequestHandler
     * @param \App\Infrastructure\RequestHandler\UnblockUserRequestHandler $unblockUserRequestHandler
     * @param \App\Infrastructure\RequestHandler\RemoveUserRequestHandler  $removeUserRequestHandler
     * @param \App\Infrastructure\RequestHandler\RestoreUserRequestHandler $restoreUserRequestHandler
     * @param \App\Infrastructure\RequestHandler\UpdateUserRequestHandler  $updateUserRequestHandler
     * @param \App\Common\Interfaces\QueryBus                              $queryBus
     */
    public function __construct(
        CreateUserRequestHandler $createUserRequestHandler,
        BlockUserRequestHandler $blockUserRequestHandler,
        UnblockUserRequestHandler $unblockUserRequestHandler,
        RemoveUserRequestHandler $removeUserRequestHandler,
        RestoreUserRequestHandler $restoreUserRequestHandler,
        UpdateUserRequestHandler $updateUserRequestHandler,
        QueryBus $queryBus
    ) {
        $this->createUserRequestHandler = $createUserRequestHandler;
        $this->blockUserRequestHandler = $blockUserRequestHandler;
        $this->unblockUserRequestHandler = $unblockUserRequestHandler;
        $this->removeUserRequestHandler = $removeUserRequestHandler;
        $this->restoreUserRequestHandler = $restoreUserRequestHandler;
        $this->updateUserRequestHandler = $updateUserRequestHandler;
        $this->queryBus = $queryBus;
    }

    /**
     * @Route("/users", name="users", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function users(): Response
    {
        $users = $this->queryBus->query(new AllUsersQuery());

        return $this->json(['users' => $users], Response::HTTP_OK);
    }

    /**
     * @Route("/users/blocked", name="blocked-users", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function blockedUsers(): Response
    {
        $users = $this->queryBus->query(new AllUsersQuery(true, false));

        return $this->json(['users' => $users], Response::HTTP_OK);
    }

    /**
     * @Route("/users/removed", name="removed-users", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removedUsers(): Response
    {
        $users = $this->queryBus->query(new AllUsersQuery(false, true));

        return $this->json(['users' => $users], Response::HTTP_OK);
    }

    /**
     * @Route("/user", name="user", methods={"GET"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function user(Request $request): Response
    {
        dd($request->getContent());

        $payload = $request->toArray();

        /** @var \App\Domain\Model\User $user */
        $user = $this->queryBus->query(new FindUserQuery($payload['identifier']));

        return $this->json(['user' => $user], Response::HTTP_OK);

    }

    /**
     * @Route("/user/create", name="user-create", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request): Response
    {
        $this->createUserRequestHandler->handle($request);

        $payload = $request->toArray();

        return $this->json(['success' => sprintf('Created user %s', $payload['email'])], Response::HTTP_OK);
    }

    /**
     * @Route("/user/update", name="user-update", methods={"PUT"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request): Response
    {
        $this->updateUserRequestHandler->handle($request);

        $payload = $request->toArray();

        return $this->json(['success' => sprintf('Updated user %s', $payload['identifier'])], Response::HTTP_OK);
    }

    /**
     * @Route("/user/block", name="user-block", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function block(Request $request): Response
    {
        $this->blockUserRequestHandler->handle($request);

        $payload = $request->toArray();

        return $this->json(['message' => sprintf('User %s is blocked', $payload['identifier'])], Response::HTTP_OK);
    }

    /**
     * @Route("/user/unblock", name="user-unblock", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function unblock(Request $request): Response
    {
        $this->unblockUserRequestHandler->handle($request);

        $payload = $request->toArray();

        return $this->json(['success' => sprintf('Unblocked user %s', $payload['identifier'])], Response::HTTP_OK);
    }

    /**
     * @Route("/user/remove", name="user-remove", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function remove(Request $request): Response
    {
        $this->removeUserRequestHandler->handle($request);

        $payload = $request->toArray();

        return $this->json(['success' => sprintf('Removed user %s', $payload['identifier'])], Response::HTTP_OK);
    }

    /**
     * @Route("/user/restore", name="user-restore", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function restore(Request $request): Response
    {
        $this->restoreUserRequestHandler->handle($request);

        $payload = $request->toArray();

        return $this->json(['success' => sprintf('Restored user %s', $payload['identifier'])], Response::HTTP_OK);
    }
}
