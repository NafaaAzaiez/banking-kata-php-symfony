<?php

declare(strict_types=1);

namespace App\Infrastructure\Fake;

use App\Domain\Account\BankAccount;
use App\Domain\Account\BankAccountRepository;

class FakeBankAccountRepository implements BankAccountRepository
{
    public const BANK_ACCOUNT_NOT_FOUND_EXCEPTION_MESSAGE = 'Bank account not found';

    public static array $accounts = [];

    public function find(string $accountNumber): BankAccount
    {
        return self::$accounts[$accountNumber] ?? throw new \DomainException(self::BANK_ACCOUNT_NOT_FOUND_EXCEPTION_MESSAGE);
    }

    public function add(BankAccount $bankAccount): void
    {
        self::$accounts[$bankAccount->getAccountNumber()] = $bankAccount;
    }
}
