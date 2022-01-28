<?php

namespace App\Infrastructure\Controller;

use App\Application\Query\FindUserQuery;
use App\Common\Contracts\QueryBus;
use App\Infrastructure\RequestHandler\RequestHandler;
use App\Infrastructure\RequestHandler\UserRequestHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 * @package App\Infrastructure\Controller
 */
class UserController extends AbstractController
{
    private QueryBus $queryBus;
    private UserRequestHandler $userRequestHandler;

    public function __construct(QueryBus $queryBus, UserRequestHandler $userRequestHandler) {
        $this->queryBus = $queryBus;
        $this->userRequestHandler = $userRequestHandler;
    }

    /**
     * @Route("/{id}", name="user", methods={"GET"})
     * @param string $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function user(string $id): JsonResponse
    {
        try {
            /** @var \App\Domain\Model\User $user */
            $user = $this->queryBus->query(new FindUserQuery($id));
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
     * @Route("/create", name="user-create", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $this->userRequestHandler->handle($request, RequestHandler::USER_CREATE_TYPE);
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
     * @Route("/update", name="user-update", methods={"PUT"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $this->userRequestHandler->handle($request, RequestHandler::USER_UPDATE_TYPE);
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
     * @Route("/block", name="user-block", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function block(Request $request): JsonResponse
    {
        try {
            $this->userRequestHandler->handle($request, RequestHandler::USER_BLOCK_TYPE);
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
     * @Route("/unblock", name="user-unblock", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function unblock(Request $request): JsonResponse
    {
        try {
            $this->userRequestHandler->handle($request, RequestHandler::USER_UNBLOCK_TYPE);
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
     * @Route("/remove", name="user-remove", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function remove(Request $request): JsonResponse
    {
        try {
            $this->userRequestHandler->handle($request, RequestHandler::USER_REMOVE_TYPE);
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
     * @Route("/restore", name="user-restore", methods={"POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function restore(Request $request): JsonResponse
    {
        try {
            $this->userRequestHandler->handle($request, RequestHandler::USER_RESTORE_TYPE);
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
