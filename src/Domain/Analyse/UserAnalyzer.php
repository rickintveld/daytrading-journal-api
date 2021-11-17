<?php

namespace App\Domain\Analyse;

use App\Common\Exception\UserNotDefinedException;
use App\Domain\Model\User;
use App\Domain\Statistic\StatisticResult;
use App\Domain\Statistic\UserProfitStatistics;

/**
 * @package App\Domain\Analyse
 */
class UserAnalyzer implements Analyse
{
    private User $user;

    private UserProfitStatistics $userProfitStatistics;

    /**
     * @param \App\Domain\Statistic\UserProfitStatistics $userProfitStatistics
     */
    public function __construct(UserProfitStatistics $userProfitStatistics)
    {
        $this->userProfitStatistics = $userProfitStatistics;
    }

    /**
     * @param Analysable $user
     * @return \App\Domain\Analyse\UserAnalyzer
     */
    public function set(Analysable $user): Analyse
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return \App\Domain\Model\User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return StatisticResult
     *
     * @throws \Exception
     */
    public function analyse(): StatisticResult
    {
        if (!$this->user) {
            throw new UserNotDefinedException();
        }

        return $this->userProfitStatistics->setUser($this->getUser())->getResult();
    }
}
