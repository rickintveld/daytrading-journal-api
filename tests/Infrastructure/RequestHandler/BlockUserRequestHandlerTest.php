<?php

namespace App\Tests\Infrastructure\RequestHandler;

use App\Application\Command\BlockUserCommand;
use App\Common\Contracts\CommandBus;
use App\Infrastructure\RequestHandler\BlockUserRequestHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Tests\Infrastructure\RequestHandler
 */
class BlockUserRequestHandlerTest extends TestCase
{
    public function testBlockUserRequest(): void
    {
        $command = new BlockUserCommand(1);
        $request = new Request([], [], [], [], [], [], '{"identifier": 1}');
        $commandBus = $this->getMockBuilder(CommandBus::class)->onlyMethods(['dispatch'])->getMock();
        $commandBus->expects($this->once())->method('dispatch')->with($command);

        $requestHandler = new BlockUserRequestHandler($commandBus);
        $requestHandler->handle($request);
    }
}
