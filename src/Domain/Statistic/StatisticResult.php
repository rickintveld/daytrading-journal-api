<?php

namespace App\Domain\Statistic;

use App\Domain\Model\Profit;
use App\Domain\Model\User;

class StatisticResult
{
    private int $total;
    private int $winners;
    private int $losers;
    private float $percentage;

    public function __construct(private User $user)
    {
    }

    public function getTotal(): int
    {
        return count($this->user->getProfits());
    }

    public function getWinners(): int
    {
        $winners = array_filter($this->user->getProfits(), static function($profit) {
            /** @var Profit $profit */
            if ($profit->getAmount() >= 0) {
                return $profit;
            }

            return 0;
        });

        array_filter($winners);

        return count($winners);
    }

    public function getLosers(): int
    {
        $losers = array_filter($this->user->getProfits(), static function($profit) {
            /** @var Profit $profit */
            if ($profit->getAmount() < 0) {
                return $profit;
            }

            return 0;
        });

        array_filter($losers);

        return count($losers);
    }

    public function getPercentage(): float
    {
        return $this->percentage;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function setWinners(int $winners): self
    {
        $this->winners = $winners;

        return $this;
    }

    public function setLosers(int $losers): self
    {
        $this->losers = $losers;

        return $this;
    }

    public function setPercentage(float $percentage): self
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function build(): self
    {
        $this->setLosers($this->getLosers())
             ->setWinners($this->getWinners())
             ->setPercentage($this->getPercentage())
             ->setTotal($this->getTotal());

        return $this;
    }
}
