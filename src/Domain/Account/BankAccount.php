<?php

declare(strict_types=1);

namespace App\Domain\Account;

class BankAccount
{
    private string $accountNumber;

    private int $balance;

    public function __construct(string $accountNumber, int $balance)
    {
        $this->accountNumber = $accountNumber;
        $this->balance = $balance;
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): void
    {
        $this->balance = $balance;
    }
}
