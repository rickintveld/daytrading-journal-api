<?php

namespace App\Application\CommandHandler;

use App\Application\Command\RestoreUserCommand;
use App\Common\Contracts\CommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Model\User;
use Psr\Log\LoggerInterface;

class RestoreUserCommandHandler implements CommandHandler
{
    public function __construct(private UserRepository $userRepository, private LoggerInterface $logger)
    {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(RestoreUserCommand $command): void
    {
        try {
            $user = $this->userRepository->findOneByIdentifier($command->getIdentifier());
            $this->handle($user);
        } catch (UserNotFoundException $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function handle(User $user): void
    {
        $user->restore();

        $this->userRepository->store($user);
    }
}
