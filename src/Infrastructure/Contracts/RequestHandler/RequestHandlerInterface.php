<?php

namespace App\Infrastructure\Contracts\RequestHandler;

use Symfony\Component\HttpFoundation\Request;

interface RequestHandlerInterface
{
    public function handle(Request $request): void;

    public function supports(int $requestType): bool;
}
