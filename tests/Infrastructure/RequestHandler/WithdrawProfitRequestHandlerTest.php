<?php

namespace App\Tests\Infrastructure\RequestHandler;

use App\Application\Command\WithdrawCommand;
use App\Common\Contracts\CommandBus;
use App\Infrastructure\RequestHandler\WithdrawRequestHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Tests\Infrastructure\RequestHandler
 */
class WithdrawProfitRequestHandlerTest extends TestCase
{
    public function testWithdrawRequest(): void
    {
        $command = new WithdrawCommand(1, 1000);
        $request = new Request([], [], [], [], [], [], '{"userId": 1, "amount": 1000}');
        $commandBus = $this->getMockBuilder(CommandBus::class)->onlyMethods(['dispatch'])->getMock();
        $commandBus->expects($this->once())->method('dispatch')->with($command);

        $requestHandler = new WithdrawRequestHandler($commandBus);
        $requestHandler->handle($request);
    }
}
