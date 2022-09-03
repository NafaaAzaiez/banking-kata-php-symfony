<?php

declare(strict_types=1);

namespace App\Domain\Account;

class BankAccount
{
    private string $accountNumber;

    private string $firstName;

    private string $lastName;

    private int $balance;

    public function __construct(string $accountNumber, string $firstName, string $lastName, int $balance)
    {
        $this->accountNumber = $accountNumber;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
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
