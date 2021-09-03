<?php

namespace App\Tests\Application\CommandHandler;

use App\Application\Command\CreateUserCommand;
use App\Application\CommandHandler\CreateUserCommandHandler;
use App\Common\Exception\UserAlreadyExists;
use App\Infrastructure\Builder\UserBuilder;
use App\Infrastructure\Entity\User;

/**
 * @package App\Tests\Application\CommandHandler
 */
class CreateUserCommandHandlerTest extends CommandHandlerProvider
{
    /**
     * @dataProvider userProvider
     *
     * @param \App\Infrastructure\Entity\User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \App\Common\Exception\UserAlreadyExists
     */
    public function testCreateUserCommand(User $user): void
    {
        $userBuilderMock = $this->getMockBuilder(UserBuilder::class)->onlyMethods(['build'])->disableOriginalConstructor()->getMock();

        $this->userRepositoryMock->expects($this->once())->method('findOneBy')->with(['email' => 'trader@journal.nl'])->willReturn('');
        $userBuilderMock->expects($this->once())->method('build')->with([
            'firstName' => 'Trader',
            'lastName' => 'Journal',
            'email' => 'trader@journal.nl',
            'password' => 'Password!@#',
            'capital' => 1000
        ])->willReturn($user);
        $this->entityManagerMock->expects($this->once())->method('persist')->with($user);
        $this->entityManagerMock->expects($this->once())->method('flush');

        $commandHandler = new CreateUserCommandHandler($this->userRepositoryMock, $this->entityManagerMock, $userBuilderMock);

        $commandHandler(new CreateUserCommand('trader@journal.nl', 'Trader', 'Journal', 1000, 'Password!@#'));
    }

    /**
     * @dataProvider userProvider
     *
     * @param \App\Infrastructure\Entity\User $user
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \App\Common\Exception\UserAlreadyExists
     */
    public function testCreateUserCommandToThrowAnError(User $user): void
    {
        $userBuilderMock = $this->getMockBuilder(UserBuilder::class)->onlyMethods(['build'])->disableOriginalConstructor()->getMock();
        $this->userRepositoryMock->expects($this->once())->method('findOneBy')->with(['email' => 'trader@journal.nl'])->willReturn($user);

        $this->expectException(UserAlreadyExists::class);

        $commandHandler = new CreateUserCommandHandler($this->userRepositoryMock, $this->entityManagerMock, $userBuilderMock);

        $commandHandler(new CreateUserCommand('trader@journal.nl', 'Trader', 'Journal', 1000, 'Password!@#'));
    }
}
