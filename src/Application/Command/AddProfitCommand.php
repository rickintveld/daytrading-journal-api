<?php

namespace App\Application\Command;

use App\Common\Contracts\Command;

/**
 * @package App\Application\Command
 */
class AddProfitCommand implements Command
{
    private string $userId;
    private float $profit;


    /**
     * @param string $userId
     * @param float  $profit
     */
    public function __construct(string $userId, float $profit)
    {
        $this->userId = $userId;
        $this->profit = $profit;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return float
     */
    public function getProfit(): float
    {
        return $this->profit;
    }
}
