<?php

declare(strict_types=1);

namespace App\Domain\Account;

interface BankAccountRepository
{
    public function find(AccountNumber $accountNumber): BankAccount;

    public function add(BankAccount $bankAccount): void;

    public function update(BankAccount $bankAccount): void;
}
