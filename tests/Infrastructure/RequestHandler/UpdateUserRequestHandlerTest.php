<?php

namespace App\Tests\Infrastructure\RequestHandler;

use App\Application\Command\UpdateUserCommand;
use App\Common\Contracts\CommandBus;
use App\Infrastructure\RequestHandler\UpdateUserRequestHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Tests\Infrastructure\RequestHandler
 */
class UpdateUserRequestHandlerTest extends TestCase
{
    public function testUpdateUserRequest(): void
    {
        $command = new UpdateUserCommand(1, 'test@test.nl', 'firstName', 'lastName', 40000, 'password');
        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            '{"userId": 1, "email": "test@test.nl", "firstName": "firstName", "lastName": "lastName", "capital": 40000, "password": "password"}'
        );
        $commandBus = $this->getMockBuilder(CommandBus::class)->onlyMethods(['dispatch'])->getMock();
        $commandBus->expects($this->once())->method('dispatch')->with($command);

        $requestHandler = new UpdateUserRequestHandler($commandBus);
        $requestHandler->handle($request);
    }
}
