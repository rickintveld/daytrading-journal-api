<?php

namespace App\Application\CommandHandler;

use App\Application\Command\BlockUserCommand;
use App\Common\Contracts\CommandHandler;
use App\Common\Contracts\PusherProviderInterface;
use App\Common\Exception\InvalidUserStateException;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Model\User;
use App\Domain\Pusher\Channel;
use App\Domain\Pusher\Event;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Pusher\Pusher;

class BlockUserCommandHandler implements CommandHandler
{
    private Pusher $pusher;

    public function __construct(
        private UserRepository $userRepository,
        private LoggerInterface $logger,
        PusherProviderInterface $pusherProviderInterface
    ) {
        $this->pusher = $pusherProviderInterface();
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

        $this->pusher->trigger(
            Channel::USER->value,
            Event::BLOCK->value,
            ['message' => sprintf('User %s is blocked', $user->getEmail())]
        );
    }
}
