<?php

declare(strict_types=1);

namespace App\Domain\Account;

interface BankAccountRepository
{
    public const BANK_ACCOUNT_NOT_FOUND_EXCEPTION_MESSAGE = 'Bank account not found';

    public function find(string $accountNumber): BankAccount;

    public function add(BankAccount $bankAccount): void;
}
