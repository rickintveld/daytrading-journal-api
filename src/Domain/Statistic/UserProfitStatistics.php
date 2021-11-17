<?php

namespace App\Domain\Statistic;

use App\Common\Exception\UserNotDefinedException;
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
        if (!$this->user) {
            throw new UserNotDefinedException();
        }

        return (new StatisticResult($this->user))->build();
    }
}
