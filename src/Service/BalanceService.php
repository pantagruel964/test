<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Balance;
use App\Repository\BalanceRepository;

class BalanceService
{
    public function __construct(private BalanceRepository $balanceRepository)
    {
    }

    public function increaseBalance(Balance $balance, float $sum): void
    {
        $newBalance = $balance->getAmount() + $sum;
        $balance->setAmount($newBalance);

        $this->balanceRepository->update($balance);
    }

    public function  decreaseBalance(Balance $balance, float $sum): void
    {
        $newBalance = $balance->getAmount() - $sum;
        $balance->setAmount($newBalance);

        $this->balanceRepository->update($balance);
    }
}
