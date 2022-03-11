<?php

namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StrategyController extends AbstractController
{
    /**
     * @Route("/strategy/add", name="strategy-add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        return $this->json([
            'status' => Response::HTTP_OK,
            'message' => ''
        ]);
    }
}
