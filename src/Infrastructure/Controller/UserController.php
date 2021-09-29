<?php

namespace App\Infrastructure\Controller;

use App\Application\Query\AllUsersQuery;
use App\Application\Query\FindUserQuery;
use App\Common\Interfaces\QueryBus;
use App\Infrastructure\RequestHandler\UserRequestHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Infrastructure\Controller
 */
class UserController extends AbstractController
{
    private QueryBus $queryBus;
    private UserRequestHandlerInterface $userRequestHandler;

    public function __construct(QueryBus $queryBus, UserRequestHandlerInterface $userRequestHandler) {
        $this->queryBus = $queryBus;
        $this->userRequestHandler = $userRequestHandler;
    }

    /**
     * @Route("/users", name="users", methods={"GET"})
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
     * @Route("/users/blocked", name="blocked-users", methods={"GET"})
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
     * @Route("/users/removed", name="removed-users", methods={"GET"})
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

    /**
     * @Route("/user", name="user", methods={"GET"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function user(Request $request): JsonResponse
    {
        $payload = $request->toArray();

        try {
            /** @var \App\Domain\Model\User $user */
            $user = $this->queryBus->query(new FindUserQuery($payload['identifier']));
        } catch (HandlerFailedException $exception) {
            return $this->json([
                'status' => Response::HTTP_NO_CONTENT,
                'message' => $exception->getPrevious()->getMessage(),
            ]);
        }

        return $this->json([
            'status' => Response::HTTP_OK,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/create", name="user-create", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $this->userRequestHandler->create($request);
        } catch (HandlerFailedException $exception) {
            return $this->json([
                'status' => Response::HTTP_NO_CONTENT,
                'message' => $exception->getPrevious()->getMessage(),
            ]);
        }

        $payload = $request->toArray();

        return $this->json([
            'status' => Response::HTTP_OK,
            'message' => sprintf('Created user %s', $payload['email']),
        ]);

    }

    /**
     * @Route("/user/update", name="user-update", methods={"PUT"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $this->userRequestHandler->update($request);
        } catch (HandlerFailedException $exception) {
            return $this->json([
                'status' => Response::HTTP_NO_CONTENT,
                'message' => $exception->getPrevious()->getMessage(),
            ]);
        }

        $payload = $request->toArray();

        return $this->json([
            'status' => Response::HTTP_OK,
            'message' => sprintf('Updated user %s', $payload['identifier']),
        ]);
    }

    /**
     * @Route("/user/block", name="user-block", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function block(Request $request): JsonResponse
    {
        try {
            $this->userRequestHandler->block($request);
        } catch (HandlerFailedException $exception) {
            return $this->json([
                'status' => Response::HTTP_NO_CONTENT,
                'message' => $exception->getPrevious()->getMessage(),
            ]);
        }

        $payload = $request->toArray();

        return $this->json([
            'status' => Response::HTTP_OK,
            'message' => sprintf('User %s is blocked', $payload['identifier']),
        ]);
    }

    /**
     * @Route("/user/unblock", name="user-unblock", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function unblock(Request $request): JsonResponse
    {
        try {
            $this->userRequestHandler->unblock($request);
        } catch (HandlerFailedException $exception) {
            return $this->json([
                'status' => Response::HTTP_NO_CONTENT,
                'message' => $exception->getPrevious()->getMessage(),
            ]);
        }

        $payload = $request->toArray();

        return $this->json([
            'status' => Response::HTTP_OK,
            'message' => sprintf('Unblocked user %s', $payload['identifier']),
        ]);
    }

    /**
     * @Route("/user/remove", name="user-remove", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function remove(Request $request): JsonResponse
    {
        try {
            $this->userRequestHandler->remove($request);
        } catch (HandlerFailedException $exception) {
            return $this->json([
                'status' => Response::HTTP_NO_CONTENT,
                'message' => $exception->getPrevious()->getMessage(),
            ]);
        }

        $payload = $request->toArray();

        return $this->json([
            'status' => Response::HTTP_OK,
            'message' => sprintf('Removed user %s', $payload['identifier']),
        ]);
    }

    /**
     * @Route("/user/restore", name="user-restore", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function restore(Request $request): JsonResponse
    {
        try {
            $this->userRequestHandler->restore($request);
        } catch (HandlerFailedException $exception) {
            return $this->json([
                'status' => Response::HTTP_NO_CONTENT,
                'message' => $exception->getPrevious()->getMessage(),
            ]);
        }

        $payload = $request->toArray();

        return $this->json([
            'status' => Response::HTTP_OK,
            'message' => sprintf('Restored user %s', $payload['identifier']),
        ]);
    }
}
