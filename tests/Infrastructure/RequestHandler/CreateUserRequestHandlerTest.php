<?php

namespace App\Tests\Infrastructure\RequestHandler;

use App\Application\Command\CreateUserCommand;
use App\Common\Contracts\CommandBus;
use App\Infrastructure\RequestHandler\CreateUserRequestHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @package App\Tests\Infrastructure\RequestHandler
 */
class CreateUserRequestHandlerTest extends TestCase
{
    public function testCreateUserRequest(): void
    {
        $command = new CreateUserCommand('test@test.nl', 'firstName', 'lastName', 40000, 'password');
        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            '{"email": "test@test.nl", "firstName": "firstName", "lastName": "lastName", "capital": 40000, "password": "password"}'
        );
        $commandBus = $this->getMockBuilder(CommandBus::class)->onlyMethods(['dispatch'])->getMock();
        $commandBus->expects($this->once())->method('dispatch')->with($command);

        $requestHandler = new CreateUserRequestHandler($commandBus);
        $requestHandler->handle($request);
    }
}
