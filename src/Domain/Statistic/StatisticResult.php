<?php

namespace App\Domain\Statistic;

use App\Domain\Model\Profit;
use App\Domain\Model\User;

/**
 * Class StatisticResult
 * @package App\Domain\Statistic
 */
class StatisticResult
{
    private int $total;

    private int $winners;

    private int $losers;

    private float $percentage;

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return count($this->user->getProfits());
    }

    /**
     * @return int
     */
    public function getWinners(): int
    {
        $winners = array_filter($this->user->getProfits(), static function($profit) {
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
    public function getLosers(): int
    {
        $losers = array_filter($this->user->getProfits(), static function($profit) {
            /** @var Profit $profit */
            if ($profit->getAmount() < 0) {
                return $profit;
            }
        });

        return count($losers);
    }

    /**
     * @return float
     */
    public function getPercentage(): float
    {
        return $this->percentage;
    }

    /**
     * @param int $total
     * @return \App\Domain\Statistic\StatisticResult
     */
    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @param int $winners
     * @return \App\Domain\Statistic\StatisticResult
     */
    public function setWinners(int $winners): self
    {
        $this->winners = $winners;

        return $this;
    }

    /**
     * @param int $losers
     * @return \App\Domain\Statistic\StatisticResult
     */
    public function setLosers(int $losers): self
    {
        $this->losers = $losers;

        return $this;
    }

    /**
     * @param float $percentage
     * @return \App\Domain\Statistic\StatisticResult
     */
    public function setPercentage(float $percentage): self
    {
        $this->percentage = $percentage;

        return $this;
    }

    /**
     * @return \App\Domain\Statistic\StatisticResult
     */
    public function build(): self
    {
        $this->setLosers($this->getLosers())
             ->setWinners($this->getWinners())
             ->setPercentage($this->getPercentage())
             ->setTotal($this->getTotal());

        return $this;
    }
}
