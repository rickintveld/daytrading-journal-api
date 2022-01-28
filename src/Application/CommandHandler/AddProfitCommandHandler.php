<?php

namespace App\Application\CommandHandler;

use App\Application\Command\AddProfitCommand;
use App\Common\Contracts\CommandHandler;
use App\Common\Exception\UserNotFoundException;
use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Model\Profit;
use App\Domain\Model\User;

/**
 * @package App\Application\CommandHandler
 */
class AddProfitCommandHandler implements CommandHandler
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
     * @param \App\Application\Command\AddProfitCommand $command
     * @throws \Exception
     */
    public function __invoke(AddProfitCommand $command): void
    {
        try {
            $user = $this->userRepository->findOneByIdentifier($command->getUserId());
            $this->handle($command, $user);
        } catch (UserNotFoundException $exception) {

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
