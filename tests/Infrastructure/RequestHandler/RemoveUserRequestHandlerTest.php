<?php

namespace App\Tests\Infrastructure\RequestHandler;

use App\Application\Command\RemoveUserCommand;
use App\Common\Interfaces\CommandBus;
use App\Infrastructure\RequestHandler\RemoveUserRequestHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Tests\Infrastructure\RequestHandler
 */
class RemoveUserRequestHandlerTest extends TestCase
{
    public function testRemoveUserRequest(): void
    {
        $command = new RemoveUserCommand(1);
        $request = new Request([], [], [], [], [], [], '{"identifier": 1}');
        $commandBus = $this->getMockBuilder(CommandBus::class)->onlyMethods(['dispatch'])->getMock();
        $commandBus->expects($this->once())->method('dispatch')->with($command);

        $requestHandler = new RemoveUserRequestHandler($commandBus);
        $requestHandler->handle($request);
    }
}
