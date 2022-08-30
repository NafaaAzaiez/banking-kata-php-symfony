<?php

declare(strict_types=1);

namespace App\Usecase\WithdrawFunds;

class WithdrawFundsRequest
{
    public string $accountNumber;

    public function __construct(string $accountNumber)
    {
        $this->accountNumber = $accountNumber;
    }
}
