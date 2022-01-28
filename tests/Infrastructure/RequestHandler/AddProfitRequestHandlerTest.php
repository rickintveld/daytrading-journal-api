<?php

namespace App\Tests\Infrastructure\RequestHandler;

use App\Application\Command\AddProfitCommand;
use App\Common\Contracts\CommandBus;
use App\Infrastructure\RequestHandler\AddProfitRequestHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Tests\Infrastructure\RequestHandler
 */
class AddProfitRequestHandlerTest extends TestCase
{
    public function testAddProfitRequest(): void
    {
        $command = new AddProfitCommand(1, 1000);
        $request = new Request([], [], [], [], [], [], '{"userId": 1, "profit": 1000}');
        $commandBus = $this->getMockBuilder(CommandBus::class)->onlyMethods(['dispatch'])->getMock();
        $commandBus->expects($this->once())->method('dispatch')->with($command);

        $requestHandler = new AddProfitRequestHandler($commandBus);
        $requestHandler->handle($request);
    }
}
