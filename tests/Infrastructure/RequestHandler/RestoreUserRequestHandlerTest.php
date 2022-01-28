<?php

namespace App\Tests\Infrastructure\RequestHandler;

use App\Application\Command\RestoreUserCommand;
use App\Common\Contracts\CommandBus;
use App\Infrastructure\RequestHandler\RestoreUserRequestHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Tests\Infrastructure\RequestHandler
 */
class RestoreUserRequestHandlerTest extends TestCase
{
    public function testRestoreUserRequest(): void
    {
        $command = new RestoreUserCommand(1);
        $request = new Request([], [], [], [], [], [], '{"identifier": 1}');
        $commandBus = $this->getMockBuilder(CommandBus::class)->onlyMethods(['dispatch'])->getMock();
        $commandBus->expects($this->once())->method('dispatch')->with($command);

        $requestHandler = new RestoreUserRequestHandler($commandBus);
        $requestHandler->handle($request);
    }
}
