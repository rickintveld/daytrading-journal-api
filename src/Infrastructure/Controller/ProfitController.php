<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\RequestHandler\AddProfitRequestHandler;
use App\Infrastructure\RequestHandler\WithdrawRequestHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Infrastructure\Controller
 */
class ProfitController extends AbstractController
{
    /** @var \App\Infrastructure\RequestHandler\AddProfitRequestHandler */
    private $addProfitRequestHandler;

    /** @var \App\Infrastructure\RequestHandler\WithdrawRequestHandler */
    private $withdrawRequestHandler;

    /**
     * @param \App\Infrastructure\RequestHandler\AddProfitRequestHandler $addProfitRequestHandler
     * @param \App\Infrastructure\RequestHandler\WithdrawRequestHandler  $withdrawRequestHandler
     */
    public function __construct(
        AddProfitRequestHandler $addProfitRequestHandler,
        WithdrawRequestHandler $withdrawRequestHandler
    ) {
        $this->addProfitRequestHandler = $addProfitRequestHandler;
        $this->withdrawRequestHandler = $withdrawRequestHandler;
    }

    /**
     * @Route("/profit/add", name="profit-add", methods={"POST"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request): Response
    {
        try {
            $this->addProfitRequestHandler->handle($request);
        } catch (\Exception $exception) {
            return $this->json(['message' => $exception->getPrevious()->getMessage()]);
        }

        $payload = $request->toArray();

        return $this->json(['message' => sprintf('Added an amount of %s to your account', $payload['profit'])]);
    }

    /**
     * @Route("/profit/withdraw", name="profit-withdraw", methods={"POST"})
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function withdraw(Request $request): Response
    {
        try {
            $this->withdrawRequestHandler->handle($request);
        } catch (\Exception $exception) {
            return $this->json(['message' => $exception->getPrevious()->getMessage()]);
        }

        $payload = $request->toArray();

        return $this->json([
            'message' => sprintf('Made a withdraw of %s from your account', $payload['amount'])
        ]);
    }
}
