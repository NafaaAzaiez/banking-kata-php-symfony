<?php

declare(strict_types=1);

namespace Tests\Common\Given;

use App\Domain\Account\AccountNumber;
use App\Infrastructure\Fake\FakeAccountNumberGenerator;

class AccountNumberGeneratorGiven
{
    private FakeAccountNumberGenerator $generator;

    public function __construct(FakeAccountNumberGenerator $generator)
    {
        $this->generator = $generator;
    }

    public static function create(FakeAccountNumberGenerator $generator): self
    {
        return new self($generator);
    }

    public function willGenerateAccountNumber(string $accountNumber): void
    {
        $this->generator->add(new AccountNumber($accountNumber));
    }
}
