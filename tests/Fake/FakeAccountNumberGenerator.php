<?php

declare(strict_types=1);

namespace Tests\Fake;

use App\Domain\Generators\AccountNumberGenerator;

class FakeAccountNumberGenerator implements AccountNumberGenerator
{
    private array $generatedAccountNumbers = [];

    public function generate(): string
    {
        if (0 === sizeof($this->generatedAccountNumbers)) {
            throw new \RuntimeException('Could not generate an account number');
        }

        return (string) array_shift($this->generatedAccountNumbers);
    }

    public function add(string $fakeAccountNumber): void
    {
        $this->generatedAccountNumbers[] = $fakeAccountNumber;
    }
}
