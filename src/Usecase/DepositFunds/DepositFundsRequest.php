<?php

declare(strict_types=1);

namespace App\Usecase\DepositFunds;

class DepositFundsRequest
{
    public string $accountNumber;

    public int $amount;

    public function __construct(string $accountNumber, int $amount)
    {
        $this->accountNumber = $accountNumber;
        $this->amount = $amount;
    }
}
