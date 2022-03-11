<?php

namespace App\Infrastructure\Controller;

use App\Application\Query\FindUserQuery;
use App\Common\Contracts\QueryBus;
use App\Infrastructure\RequestHandler\RequestHandler;
use App\Infrastructure\RequestHandler\UserRequestHandler;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    public function __construct(
        private QueryBus $queryBus,
        private UserRequestHandler $userRequestHandler,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @Route("/{id}", name="user", methods={"GET"})
     */
    public function user(string $id): JsonResponse
    {
        try {
            /** @var \App\Domain\Model\User $user */
            $user = $this->queryBus->query(new FindUserQuery($id));
        } catch (HandlerFailedException $exception) {
            $this->logger->error($exception->getPrevious()->getMessage());

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
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $this->userRequestHandler->handle($request, RequestHandler::USER_CREATE_TYPE);
        } catch (HandlerFailedException $exception) {
            $this->logger->error($exception->getPrevious()->getMessage());

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
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $this->userRequestHandler->handle($request, RequestHandler::USER_UPDATE_TYPE);
        } catch (HandlerFailedException $exception) {
            $this->logger->error($exception->getPrevious()->getMessage());

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
     */
    public function block(Request $request): JsonResponse
    {
        try {
            $this->userRequestHandler->handle($request, RequestHandler::USER_BLOCK_TYPE);
        } catch (HandlerFailedException $exception) {
            $this->logger->error($exception->getPrevious()->getMessage());

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
     */
    public function unblock(Request $request): JsonResponse
    {
        try {
            $this->userRequestHandler->handle($request, RequestHandler::USER_UNBLOCK_TYPE);
        } catch (HandlerFailedException $exception) {
            $this->logger->error($exception->getPrevious()->getMessage());

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
     */
    public function remove(Request $request): JsonResponse
    {
        try {
            $this->userRequestHandler->handle($request, RequestHandler::USER_REMOVE_TYPE);
        } catch (HandlerFailedException $exception) {
            $this->logger->error($exception->getPrevious()->getMessage());

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
     */
    public function restore(Request $request): JsonResponse
    {
        try {
            $this->userRequestHandler->handle($request, RequestHandler::USER_RESTORE_TYPE);
        } catch (HandlerFailedException $exception) {
            $this->logger->error($exception->getPrevious()->getMessage());

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
