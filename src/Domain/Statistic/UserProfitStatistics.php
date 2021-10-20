<?php

namespace App\Domain\Statistic;

use App\Domain\Model\Profit;
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
     * @return \App\Domain\Model\User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return array<string, int|float>
     */
    public function getResult(): array
    {
        return [
            'losers' => $this->losers(),
            'total' => $this->total(),
            'winners' => $this->winners(),
            'winPercentage' => $this->winPercentage()
        ];
    }

    /**
     * @return int
     */
    private function winners(): int
    {
        $winners = array_filter($this->getUser()->getProfits(), static function($profit) {
            /** @var Profit $profit */
            if ($profit->getAmount() >= 0) {
                return $profit;
            }
        });

        return count($winners);
    }

    /**
     * @return int
     */
    private function losers(): int
    {
        $losers = array_filter($this->getUser()->getProfits(), static function($profit) {
            /** @var Profit $profit */
            if ($profit->getAmount() < 0) {
                return $profit;
            }
        });

        return count($losers);
    }

    /**
     * @return int
     */
    private function total(): int
    {
        return count($this->getUser()->getProfits());
    }

    /**
     * @return float
     */
    private function winPercentage(): float
    {
        return ($this->winners() / $this->total()) * 100;
    }
}
