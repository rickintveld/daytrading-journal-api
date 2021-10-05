<?php

namespace App\Tests\Application\CommandHandler;

use App\Application\Command\AddProfitCommand;
use App\Application\CommandHandler\AddProfitCommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Model\User;

/**
 * @package App\Tests\Application\CommandHandler
 */
class AddProfitCommandHandlerTest extends CommandHandlerProvider
{
    /**
     * @dataProvider userProvider
     *
     * @param \App\Domain\Model\User $user
     * @throws \Exception
     */
    public function testAddProfitCommand(User $user): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneByIdentifier')->with(1)->willReturn($user);
        $this->userRepositoryMock->expects($this->once())->method('store');

        $commandHandler = new AddProfitCommandHandler($this->userRepositoryMock);

        $commandHandler(new AddProfitCommand(1, 1000));
    }

    /**
     * @throws \Exception
     */
    public function testAddProfitCommandToThrowAnError(): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneByIdentifier')->with(1)->willReturn('');

        $this->expectException(UserNotFoundException::class);

        $commandHandler = new AddProfitCommandHandler($this->userRepositoryMock);

        $commandHandler(new AddProfitCommand(1, 1000));
    }
}
