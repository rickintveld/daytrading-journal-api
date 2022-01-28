<?php

namespace App\Domain\Command;

use App\Domain\Contracts\Analyse\Analyse;
use App\Domain\Contracts\Repository\UserRepository;
use App\Domain\Statistic\StatisticResult;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @package App\Domain\Command
 */
class AnalyseUserProfitCommand extends Command
{
    protected static $defaultName = 'analyse:user:profit';
    protected static $defaultDescription = 'Analyse a specific user or all users';

    private Analyse $analyser;
    private UserRepository $userRepository;

    private const HEADERS = [
        'total',
        'losers',
        'winners',
        'winPercentage'
    ];

    /**
     * AnalyseUserProfitCommand constructor.
     * @param \App\Domain\Contracts\Analyse\Analyse           $analyser
     * @param \App\Domain\Contracts\Repository\UserRepository $userRepository
     */
    public function __construct(Analyse $analyser, UserRepository $userRepository)
    {
        parent::__construct(self::$defaultName);

        $this->analyser = $analyser;
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this->addOption('userId', null, InputOption::VALUE_REQUIRED, 'Analyse a user by ID');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     * @throws \App\Common\Exception\UserNotFoundException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
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
     * @param int $userId
     *
     * @return \App\Domain\Statistic\StatisticResult
     *
     * @throws \App\Common\Exception\UserNotFoundException
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function getAnalyzedUser(int $userId): StatisticResult
    {
        return $this->analyser->set(
            $this->userRepository->findOneByIdentifier($userId)
        )->analyse();
    }

    /**
     * @return array[StatisticResult]
     */
    protected function getAllAnalyzedUsers(): array
    {
        return array_map(function($user) {
            return $this->analyser->set($user)->analyse();
        }, $this->userRepository->findAllActive(false, false));
    }
}
