<?php

declare(strict_types=1);

namespace App\Domain\Account;

class BankAccount
{
    private AccountNumber $accountNumber;

    private string $firstName;

    private string $lastName;

    private int $balance;

    public function __construct(AccountNumber $accountNumber, string $firstName, string $lastName, int $balance)
    {
        $this->accountNumber = $accountNumber;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->balance = $balance;
    }

    public function getAccountNumber(): AccountNumber
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

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}
