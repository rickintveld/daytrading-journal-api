<?php

namespace App\Domain\Statistic;

use App\Common\Exception\UserNotDefinedException;
use App\Domain\Contracts\Statistic\Statistic;
use App\Domain\Model\User;

class UserProfitStatistics implements Statistic
{
    private User $user;

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getResult(): StatisticResult
    {
        return (new StatisticResult($this->user))->build();
    }
}
