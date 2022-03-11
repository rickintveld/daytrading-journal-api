<?php

namespace App\Domain\Analyse;

use App\Common\Exception\UserNotDefinedException;
use App\Domain\Contracts\Analyse\Analysable;
use App\Domain\Contracts\Analyse\Analyse;
use App\Domain\Model\User;
use App\Domain\Statistic\StatisticResult;
use App\Domain\Statistic\UserProfitStatistics;

class UserAnalyzer implements Analyse
{
    private ?User $user;

    public function __construct(private UserProfitStatistics $userProfitStatistics)
    {
    }

    public function set(Analysable $analysable): Analyse
    {
        $this->user = $analysable;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
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
