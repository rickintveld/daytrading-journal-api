<?php

namespace App\Application\CommandHandler;

use App\Application\Command\BlockUserCommand;
use App\Common\Contracts\CommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Model\User;

/**
 * @package App\Application\CommandHandler
 */
class BlockUserCommandHandler implements CommandHandler
{
    private UserRepository $userRepository;

    /**
     * @param \App\Domain\Contracts\Repository\UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param \App\Application\Command\BlockUserCommand $command
     * @throws \Exception
     */
    public function __invoke(BlockUserCommand $command): void
    {
        try {
            $user = $this->userRepository->findOneByIdentifier($command->getIdentifier());
            $this->handle($user);
        } catch (UserNotFoundException $exception) {

        }

    }

    /**
     * @param \App\Domain\Model\User $user
     *
     * @throws \App\Common\Exception\InvalidUserStateException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function handle(User $user): void
    {
        $user->block();

        $this->userRepository->store($user);
    }
}
