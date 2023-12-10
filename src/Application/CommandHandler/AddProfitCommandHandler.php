<?php

namespace App\Application\CommandHandler;

use App\Application\Command\AddProfitCommand;
use App\Common\Contracts\CommandHandler;
use App\Common\Contracts\PusherProviderInterface;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Model\Profit;
use App\Domain\Model\User;
use App\Domain\Pusher\Channel;
use App\Domain\Pusher\Event;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Psr\Log\LoggerInterface;
use Pusher\Pusher;

class AddProfitCommandHandler implements CommandHandler
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
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function __invoke(AddProfitCommand $command): void
    {
        try {
            $user = $this->userRepository->findOneByIdentifier($command->getUserId());
            $this->handle($command, $user);
        } catch (UserNotFoundException $exception) {
            $this->logger->error($exception->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    private function handle(AddProfitCommand $command, User $user): void
    {
        $profit = new Profit($user->getId(), $command->getProfit());

        $user->addProfit($profit);

        $this->userRepository->store($user);

        $this->pusher->trigger(
            Channel::PROFIT->value,
            Event::NEW->value,
            ['message' => sprintf('Added %s profit', $profit->getAmount())]
        );
    }
}
