<?php

declare(strict_types=1);

namespace App\Domain\Account;

use App\Domain\Exception\RequestValidationException;

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

    public function deposit(int $amount): void
    {
        $this->balance += $amount;
    }

    public function withdraw(int $amount): void
    {
        if ($amount > $this->balance) {
            throw RequestValidationException::withMessage(RequestValidationException::INSUFFICIENT_FUNDS);
        }

        $this->balance -= $amount;
    }

    public function getAccountHolderName(): AccountHolderName
    {
        return $this->accountHolderName;
    }
}
