<?php

namespace App\Domain\Command;

use App\Common\Exception\UserNotFoundException;
use App\Domain\Contracts\Analyse\Analyse;
use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Statistic\StatisticResult;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AnalyseUserProfitCommand extends Command
{
    protected static $defaultName = 'analyse:user:profit';
    protected static $defaultDescription = 'Analyse a specific user or all users';

    private const HEADERS = [
        'total',
        'losers',
        'winners',
        'winPercentage'
    ];

    public function __construct(private Analyse $analyser, private UserRepository $userRepository)
    {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this->addOption('userId', null, InputOption::VALUE_REQUIRED, 'Analyse a user by ID');
    }

    /**
     * @throws UserNotFoundException
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($userId = $input->getOption('userId')) {
            $io->table(self::HEADERS, $this->getAnalyzedUser($userId));
        } else {
            $io->table(self::HEADERS, $this->getAllAnalyzedUsers());
        }

        return Command::SUCCESS;
    }

    /**
     * @throws UserNotFoundException
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    protected function getAnalyzedUser(int $userId): StatisticResult
    {
        return $this->analyser->set(
            $this->userRepository->findOneByIdentifier($userId)
        )->analyse();
    }

    /**
     * @return array<StatisticResult>
     */
    protected function getAllAnalyzedUsers(): array
    {
        return array_map(function($user) {
            return $this->analyser->set($user)->analyse();
        }, $this->userRepository->findAllActive(false, false));
    }
}
