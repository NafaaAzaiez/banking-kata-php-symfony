<?php

declare(strict_types=1);

namespace App\Domain\Account;

class BankAccount
{
    private AccountNumber $accountNumber;

    private AccountHolderName $accountHolderName;

    private int $balance;

    public function __construct(AccountNumber $accountNumber, AccountHolderName $accountHolderName, int $balance)
    {
        $this->accountNumber = $accountNumber;
        $this->accountHolderName = $accountHolderName;
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

    public function getAccountHolderName(): AccountHolderName
    {
        return $this->accountHolderName;
    }
}
