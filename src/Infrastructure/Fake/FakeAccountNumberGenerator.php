<?php

declare(strict_types=1);

namespace App\Infrastructure\Fake;

use App\Domain\Account\AccountNumber;
use App\Domain\Account\AccountNumberGenerator;

class FakeAccountNumberGenerator implements AccountNumberGenerator
{
    public const COULD_NOT_GENERATE_NUMBER_MESSAGE = 'Could not generate an account number';

    private array $generatedAccountNumbers = [];

    public function generate(): AccountNumber
    {
        if (0 === sizeof($this->generatedAccountNumbers)) {
            throw new \RuntimeException(self::COULD_NOT_GENERATE_NUMBER_MESSAGE);
        }

        return array_shift($this->generatedAccountNumbers);
    }

    public function add(AccountNumber $fakeAccountNumber): void
    {
        $this->generatedAccountNumbers[] = $fakeAccountNumber;
    }
}
