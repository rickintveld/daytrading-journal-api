<?php

namespace App\Application\CommandHandler;

use App\Application\Command\AddProfitCommand;
use App\Common\Contracts\CommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Model\Profit;
use App\Domain\Model\User;
use Psr\Log\LoggerInterface;

/**
 * @package App\Application\CommandHandler
 */
class AddProfitCommandHandler implements CommandHandler
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
     * @param \App\Application\Command\AddProfitCommand $command
     * @throws \Exception
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
     * @param \App\Application\Command\AddProfitCommand $command
     * @param \App\Domain\Model\User                    $user
     * @throws \Exception
     */
    private function handle(AddProfitCommand $command, User $user): void
    {
        $profit = new Profit($user->getId(), $command->getProfit());

        $user->addProfit($profit);

        $this->userRepository->store($user);
    }
}
