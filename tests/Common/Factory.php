<?php

declare(strict_types=1);

namespace Tests\Common;

use App\Domain\Account\BankAccount;

class Factory
{
    private const FIRST_NAME = 'JOHN';

    private const LAST_NAME = 'SMITH';

    private const BALANCE = 100;

    public static function createDefaultBankAccount(string $accountNumber, ?int $balance = self::BALANCE): BankAccount
    {
        return new BankAccount($accountNumber, self::FIRST_NAME, self::LAST_NAME, $balance);
    }
}
