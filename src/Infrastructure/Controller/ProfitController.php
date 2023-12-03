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
 * @Route("/profit")
 */
class ProfitController extends AbstractController
{
    public function __construct(private ProfitRequestHandler $profitRequestHandler, private LoggerInterface $logger)
    {
    }

    /**
     * @Route("/add", name="profit-add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        try {
            $this->profitRequestHandler->handle($request, RequestHandler::PROFIT_ADD_TYPE);
        } catch (HandlerFailedException $exception) {
            $this->logger->error($exception->getPrevious()->getMessage());

            return $this->json(['message' => $exception->getPrevious()->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $payload = $request->toArray();

        return $this->json(['message' => sprintf('Added an amount of %s to your account', $payload['profit'])], Response::HTTP_OK);
    }

    /**
     * @Route("/withdraw", name="profit-withdraw", methods={"POST"})
     */
    public function withdraw(Request $request): JsonResponse
    {
        try {
            $this->profitRequestHandler->handle($request, RequestHandler::PROFIT_WITHDRAW_TYPE);
        } catch (HandlerFailedException $exception) {
            $this->logger->error($exception->getPrevious()->getMessage());

            return $this->json(['message' => $exception->getPrevious()->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $payload = $request->toArray();

        return $this->json(['message' => sprintf('Made a withdraw of %s from your account', $payload['amount'])], Response::HTTP_OK);
    }
}
