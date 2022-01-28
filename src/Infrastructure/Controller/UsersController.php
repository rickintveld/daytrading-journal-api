<?php

namespace App\Infrastructure\Controller;

use App\Application\Query\AllUsersQuery;
use App\Common\Contracts\QueryBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/users")
 * @package App\Infrastructure\Controller
 */
class UsersController extends AbstractController
{
    private QueryBus $queryBus;

    public function __construct(QueryBus $queryBus) {
        $this->queryBus = $queryBus;
    }

    /**
     * @Route("/", name="users", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function users(): JsonResponse
    {
        try {
            $users = $this->queryBus->query(new AllUsersQuery());
        } catch (HandlerFailedException $exception) {
            return $this->json([
                'status' => Response::HTTP_NO_CONTENT,
                'message' => $exception->getPrevious()->getMessage(),
            ]);
        }

        return $this->json([
            'status' => Response::HTTP_OK,
            'users' => $users,
        ]);
    }

    /**
     * @Route("/blocked", name="blocked-users", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function blockedUsers(): JsonResponse
    {
        try {
            $users = $this->queryBus->query(new AllUsersQuery(true, false));
        } catch (HandlerFailedException $exception) {
            return $this->json([
                'status' => Response::HTTP_NO_CONTENT,
                'message' => $exception->getPrevious()->getMessage(),
            ]);
        }

        return $this->json([
            'status' => Response::HTTP_OK,
            'users' => $users,
        ]);
    }

    /**
     * @Route("/removed", name="removed-users", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removedUsers(): JsonResponse
    {
        try {
            $users = $this->queryBus->query(new AllUsersQuery(false, true));
        } catch (HandlerFailedException $exception) {
            return $this->json([
                'status' => Response::HTTP_NO_CONTENT,
                'message' => $exception->getPrevious()->getMessage(),
            ]);
        }

        return $this->json([
            'status' => Response::HTTP_OK,
            'users' => $users,
        ]);
    }
}
