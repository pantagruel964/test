<?php

declare(strict_types=1);

namespace App\Entity;

class MoneyTransfer
{
    public function __construct(private User $sender, private User $recipient, private float $amount)
    {
    }

    public function getSender(): User
    {
        return $this->sender;
    }

    public function getRecipient(): User
    {
        return $this->recipient;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
