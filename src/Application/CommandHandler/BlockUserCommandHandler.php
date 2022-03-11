<?php

namespace App\Application\CommandHandler;

use App\Application\Command\BlockUserCommand;
use App\Common\Contracts\CommandHandler;
use App\Common\Exception\InvalidUserStateException;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Model\User;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;

class BlockUserCommandHandler implements CommandHandler
{
    public function __construct(private UserRepository $userRepository, private LoggerInterface $logger)
    {
    }

    /**
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
     * @throws InvalidUserStateException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function handle(User $user): void
    {
        $user->block();

        $this->userRepository->store($user);
    }
}
