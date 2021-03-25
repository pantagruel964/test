<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\MoneyTransfer;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;

class MoneyTransferService
{
    public function __construct(
        private TransactionRepository $transactionRepository,
        private BalanceService $balanceService,
    ) {
    }

    public function sendToUser(MoneyTransfer $moneyTransfer)
    {
        $this->balanceService->decreaseBalance($moneyTransfer->getSender()->getBalance(), $moneyTransfer->getAmount());
        $this->balanceService->increaseBalance(
            $moneyTransfer->getRecipient()->getBalance(),
            $moneyTransfer->getAmount()
        );

        $transaction = new Transaction(
            $moneyTransfer->getSender(),
            $moneyTransfer->getRecipient(),
            $moneyTransfer->getAmount(),
            Transaction::STATUS_SUCCESS,
        );

        $this->transactionRepository->create($transaction);
    }


}
