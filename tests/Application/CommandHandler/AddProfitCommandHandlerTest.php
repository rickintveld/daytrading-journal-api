<?php

namespace App\Tests\Application\CommandHandler;

use App\Application\Command\AddProfitCommand;
use App\Application\CommandHandler\AddProfitCommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Infrastructure\Entity\User;

/**
 * @package App\Tests\Application\CommandHandler
 */
class AddProfitCommandHandlerTest extends CommandHandlerProvider
{
    /**
     * @dataProvider userProvider
     *
     * @param \App\Infrastructure\Entity\User $user
     * @throws \Exception
     */
    public function testAddProfitCommand(User $user): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneBy')->with(['id' => 1])->willReturn($user);
        $this->entityManagerMock->expects($this->once())->method('persist')->with($user);
        $this->entityManagerMock->expects($this->once())->method('flush');

        $commandHandler = new AddProfitCommandHandler($this->userRepositoryMock, $this->entityManagerMock);

        $commandHandler(new AddProfitCommand(1, 1000));
    }

    public function testAddProfitCommandToThrowAnError(): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneBy')->with(['id' => 1])->willReturn('');

        $this->expectException(UserNotFoundException::class);

        $commandHandler = new AddProfitCommandHandler($this->userRepositoryMock, $this->entityManagerMock);

        $commandHandler(new AddProfitCommand(1, 1000));
    }
}
