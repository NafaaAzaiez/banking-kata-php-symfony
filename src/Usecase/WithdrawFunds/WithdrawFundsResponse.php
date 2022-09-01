<?php

declare(strict_types=1);

namespace App\Usecase\WithdrawFunds;

class WithdrawFundsResponse
{
    public int $balance;

    public function __construct(int $balance)
    {
        $this->balance = $balance;
    }
}
