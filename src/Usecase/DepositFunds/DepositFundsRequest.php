<?php

declare(strict_types=1);

namespace App\Usecase\DepositFunds;

class DepositFundsRequest
{
    public string $accountNumber;

    public function __construct(string $accountNumber)
    {
        $this->accountNumber = $accountNumber;
    }
}
