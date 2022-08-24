<?php

declare(strict_types=1);

namespace App\Infrastructure\Fake;

use App\Domain\Account\BankAccount;
use App\Domain\Account\BankAccountRepository;

class FakeBankAccountRepository implements BankAccountRepository
{
    public static array $accounts = [];

    public function find(string $accountNumber): BankAccount
    {
        return self::$accounts[$accountNumber] ?? throw new \DomainException('Bank account not found');
    }

    public function add(BankAccount $bankAccount): void
    {
        self::$accounts[$bankAccount->getAccountNumber()] = $bankAccount;
    }
}
