<?php

namespace App\Domain\Statistic;

use App\Common\Exception\UserNotDefinedException;
use App\Domain\Contracts\Statistic\Statistic;
use App\Domain\Model\User;

/**
 * @package App\Domain\Statistic
 */
class UserProfitStatistics implements Statistic
{
    private User $user;

    /**
     * @param \App\Domain\Model\User $user
     * @return \App\Domain\Statistic\UserProfitStatistics
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return StatisticResult
     * @throws \Exception
     */
    public function getResult(): StatisticResult
    {
        if (!$this->user instanceof User) {
            throw new UserNotDefinedException();
        }

        return (new StatisticResult($this->user))->build();
    }
}
