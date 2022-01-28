<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\RequestHandler\ProfitRequestHandler;
use App\Infrastructure\RequestHandler\RequestHandler;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Infrastructure\Controller
 */
class ProfitController extends AbstractController
{
    private ProfitRequestHandler $profitRequestHandler;
    private LoggerInterface $logger;

    /**
     * @param \App\Infrastructure\RequestHandler\ProfitRequestHandler $profitRequestHandler
     * @param \Psr\Log\LoggerInterface                                $logger
     */
    public function __construct(ProfitRequestHandler $profitRequestHandler, LoggerInterface $logger)
    {
        $this->profitRequestHandler = $profitRequestHandler;
        $this->logger = $logger;
    }

    /**
     * @Route("/profit/add", name="profit-add", methods={"POST"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        try {
            $this->profitRequestHandler->handle($request, RequestHandler::PROFIT_ADD_TYPE);
        } catch (HandlerFailedException $exception) {
            $this->logger->error($exception->getPrevious()->getMessage());

            return $this->json([
                'status' => Response::HTTP_NO_CONTENT,
                'message' => $exception->getPrevious()->getMessage()]
            );
        }

        $payload = $request->toArray();

        return $this->json([
            'status' => Response::HTTP_OK,
            'message' => sprintf('Added an amount of %s to your account', $payload['profit'])
        ]);
    }

    /**
     * @Route("/profit/withdraw", name="profit-withdraw", methods={"POST"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function withdraw(Request $request): JsonResponse
    {
        try {
            $this->profitRequestHandler->handle($request, RequestHandler::PROFIT_WITHDRAW_TYPE);
        } catch (HandlerFailedException $exception) {
            $this->logger->error($exception->getPrevious()->getMessage());

            return $this->json([
                'status' => Response::HTTP_NO_CONTENT,
                'message' => $exception->getPrevious()->getMessage()
            ]);
        }

        $payload = $request->toArray();

        return $this->json([
            'status' => Response::HTTP_OK,
            'message' => sprintf('Made a withdraw of %s from your account', $payload['amount'])
        ]);
    }
}
