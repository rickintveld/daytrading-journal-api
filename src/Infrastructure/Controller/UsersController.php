<?php

namespace App\Infrastructure\Controller;

use App\Application\Query\AllUsersQuery;
use App\Common\Contracts\QueryBus;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users")
 */
class UsersController extends AbstractController
{
    public function __construct(private QueryBus $queryBus, private LoggerInterface $logger)
    {
    }

    /**
     * @Route("/", name="users", methods={"GET"})
     */
    public function users(): JsonResponse
    {
        try {
            $users = $this->queryBus->query(new AllUsersQuery());
        } catch (HandlerFailedException $exception) {
            $this->logger->error($exception->getPrevious()->getMessage());

            return $this->json(['message' => $exception->getPrevious()->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['users' => $users], Response::HTTP_OK);
    }

    /**
     * @Route("/blocked", name="blocked-users", methods={"GET"})
     */
    public function blockedUsers(): JsonResponse
    {
        try {
            $users = $this->queryBus->query(new AllUsersQuery(isBlocked: true));
        } catch (HandlerFailedException $exception) {
            $this->logger->error($exception->getPrevious()->getMessage());

            return $this->json(['message' => $exception->getPrevious()->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['users' => $users], Response::HTTP_OK);
    }

    /**
     * @Route("/removed", name="removed-users", methods={"GET"})
     */
    public function removedUsers(): JsonResponse
    {
        try {
            $users = $this->queryBus->query(new AllUsersQuery(isRemoved: true));
        } catch (HandlerFailedException $exception) {
            $this->logger->error($exception->getPrevious()->getMessage());

            return $this->json(['message' => $exception->getPrevious()->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(['users' => $users], Response::HTTP_OK);
    }
}
