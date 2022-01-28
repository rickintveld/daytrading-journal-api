<?php

namespace App\Application\CommandHandler;

use App\Application\Command\BlockUserCommand;
use App\Common\Contracts\CommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Model\User;
use Psr\Log\LoggerInterface;

/**
 * @package App\Application\CommandHandler
 */
class BlockUserCommandHandler implements CommandHandler
{
    private UserRepository $userRepository;
    private LoggerInterface $logger;

    /**
     * @param \App\Domain\Contracts\Repository\UserRepository $userRepository
     * @param \Psr\Log\LoggerInterface                        $logger
     */
    public function __construct(UserRepository $userRepository, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
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
            $this->logger->error($exception->getMessage());
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
