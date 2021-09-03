<?php

namespace App\Tests\Infrastructure\RequestHandler;

use App\Application\Command\UnBlockUserCommand;
use App\Common\Interfaces\CommandBus;
use App\Infrastructure\RequestHandler\UnblockUserRequestHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Tests\Infrastructure\RequestHandler
 */
class UnblockUserRequestHandlerTest extends TestCase
{
    public function testUnblockUserRequest(): void
    {
        $command = new UnblockUserCommand(1);
        $request = new Request([], [], [], [], [], [], '{"identifier": 1}');
        $commandBus = $this->getMockBuilder(CommandBus::class)->onlyMethods(['dispatch'])->getMock();
        $commandBus->expects($this->once())->method('dispatch')->with($command);

        $requestHandler = new UnblockUserRequestHandler($commandBus);
        $requestHandler->handle($request);
    }
}
