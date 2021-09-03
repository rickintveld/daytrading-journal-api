<?php

namespace App\Tests\Application\CommandHandler;

use App\Application\Command\UnBlockUserCommand;
use App\Application\CommandHandler\UnblockUserCommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Infrastructure\Entity\User;

class UnblockUserCommandHandlerTest extends CommandHandlerProvider
{
    /**
     * @dataProvider userProvider
     * @param \App\Infrastructure\Entity\User $user
     * @throws \Exception
     */
    public function testUnblockUserCommand(User $user): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneBy')->with(['id' => 1])->willReturn($user);
        $this->entityManagerMock->expects($this->once())->method('persist')->with($user);
        $this->entityManagerMock->expects($this->once())->method('flush');

        $commandHandler = new UnblockUserCommandHandler($this->userRepositoryMock, $this->entityManagerMock);

        $commandHandler(new UnblockUserCommand(1));
    }

    /**
     * @dataProvider userProvider
     *
     * @param \App\Infrastructure\Entity\User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function testUnblockUserCommandWhileRemoved(User $user): void
    {
        $user->setRemoved(true);

        $this->userRepositoryMock->expects($this->once())->method('findOneBy')->with(['id' => 1])->willReturn($user);

        $this->expectException(\Exception::class);

        $commandHandler = new UnblockUserCommandHandler($this->userRepositoryMock, $this->entityManagerMock);

        $commandHandler(new UnblockUserCommand(1));
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testUnblockUserCommandToThrowAnError(): void
    {
        $this->userRepositoryMock->expects($this->once())->method('findOneBy')->with(['id' => 1])->willReturn('');

        $this->expectException(UserNotFoundException::class);

        $commandHandler = new UnblockUserCommandHandler($this->userRepositoryMock, $this->entityManagerMock);

        $commandHandler(new UnblockUserCommand(1));
    }
}
