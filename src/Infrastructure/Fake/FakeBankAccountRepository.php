<?php

declare(strict_types=1);

namespace App\Infrastructure\Fake;

use App\Domain\Account\BankAccount;
use App\Domain\Account\BankAccountRepository;
use App\Domain\Exception\RepositoryException;

class FakeBankAccountRepository implements BankAccountRepository
{
    public static array $accounts = [];

    public function find(string $accountNumber): BankAccount
    {
        if (!key_exists($accountNumber, self::$accounts)) {
            throw RepositoryException::withMessage(RepositoryException::BANK_ACCOUNT_NOT_FOUND_EXCEPTION_MESSAGE);
        }

        $bankAccount = self::$accounts[$accountNumber];

        return clone $bankAccount;
    }

    public function add(BankAccount $bankAccount): void
    {
        if (key_exists($bankAccount->getAccountNumber(), self::$accounts)) {
            throw RepositoryException::withMessage(RepositoryException::BANK_ACCOUNT_ALREADY_EXISTS);
        }

        $clonedBankAccount = clone $bankAccount;

        self::$accounts[$clonedBankAccount->getAccountNumber()] = $clonedBankAccount;
    }

    public function update(BankAccount $bankAccount): void
    {
        if (!key_exists($bankAccount->getAccountNumber(), self::$accounts)) {
            throw RepositoryException::withMessage(RepositoryException::CAN_NOT_UPDATE_NON_EXISTENT_BANK_ACCOUNT);
        }

        $clonedBankAccount = clone $bankAccount;
        self::$accounts[$clonedBankAccount->getAccountNumber()] = $clonedBankAccount;
    }

    public static function reset(): void
    {
        self::$accounts = [];
    }
}
