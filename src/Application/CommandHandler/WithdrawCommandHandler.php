<?php

namespace App\Application\CommandHandler;

use App\Application\Command\WithdrawCommand;
use App\Common\Contracts\CommandHandler;
use App\Common\Contracts\PusherProviderInterface;
use App\Common\Exception\InvalidFundsException;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Model\User;
use App\Domain\Pusher\Channel;
use App\Domain\Pusher\Event;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Pusher\Pusher;

class WithdrawCommandHandler implements CommandHandler
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
     * @throws InvalidFundsException
     * @throws NoResultException
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function __invoke(WithdrawCommand $command): void
    {
        try {
            $user = $this->userRepository->findOneByIdentifier($command->getUserId());
            $this->handle($command, $user);
        } catch (UserNotFoundException $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * @throws InvalidFundsException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    private function handle(WithdrawCommand $command, User $user): void
    {
        $user->withdraw($command->getAmount());

        $this->userRepository->store($user);

        $this->pusher->trigger(
            Channel::PROFIT->value,
            Event::WITHDRAW->value,
            ['message' => sprintf('Withdraw %s from account %d', $command->getAmount(), $command->getUserId())]
        );
    }
}
