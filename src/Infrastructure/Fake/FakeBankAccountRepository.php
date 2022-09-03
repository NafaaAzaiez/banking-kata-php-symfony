<?php

declare(strict_types=1);

namespace App\Infrastructure\Fake;

use App\Domain\Account\AccountNumber;
use App\Domain\Account\BankAccount;
use App\Domain\Account\BankAccountRepository;
use App\Domain\Exception\RepositoryException;

class FakeBankAccountRepository implements BankAccountRepository
{
    public static array $accounts = [];

    public function find(AccountNumber $accountNumber): BankAccount
    {
        if (!key_exists($accountNumber->value(), self::$accounts)) {
            throw RepositoryException::withMessage(RepositoryException::BANK_ACCOUNT_NOT_FOUND_EXCEPTION_MESSAGE);
        }

        $bankAccount = self::$accounts[$accountNumber->value()];

        return clone $bankAccount;
    }

    public function add(BankAccount $bankAccount): void
    {
        if (key_exists($bankAccount->getAccountNumber()->value(), self::$accounts)) {
            throw RepositoryException::withMessage(RepositoryException::BANK_ACCOUNT_ALREADY_EXISTS);
        }

        $clonedBankAccount = clone $bankAccount;

        self::$accounts[$clonedBankAccount->getAccountNumber()->value()] = $clonedBankAccount;
    }

    public function update(BankAccount $bankAccount): void
    {
        if (!key_exists($bankAccount->getAccountNumber()->value(), self::$accounts)) {
            throw RepositoryException::withMessage(RepositoryException::CAN_NOT_UPDATE_NON_EXISTENT_BANK_ACCOUNT);
        }

        $clonedBankAccount = clone $bankAccount;
        self::$accounts[$clonedBankAccount->getAccountNumber()->value()] = $clonedBankAccount;
    }

    public static function reset(): void
    {
        self::$accounts = [];
    }
}
