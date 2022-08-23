<?php

declare(strict_types=1);

namespace App\Domain\Entity;

class BankAccount
{
    private string $accountNumber;

    public function __construct(string $accountNumber)
    {
        $this->accountNumber = $accountNumber;
    }

    public function getAccountNumber(): string
    {
        return $this->accountNumber;
    }
}
