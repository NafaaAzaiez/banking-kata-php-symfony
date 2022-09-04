<?php

declare(strict_types=1);

namespace Tests\Builder\Entity;

use App\Domain\Account\AccountNumber;
use App\Domain\Account\BankAccount;

class BankAccountBuilder
{
    private const ACCOUNT_NUMBER = 'X4567GT99';

    private const FIRST_NAME = 'JOHN';

    private const LAST_NAME = 'SMITH';

    private const BALANCE = 100;

    private AccountNumber $accountNumber;

    private String $firstName;

    private String $lastName;

    private int $balance;

    private function __construct()
    {
        $this->withAccountNumber(self::ACCOUNT_NUMBER);
        $this->withFirstName(self::FIRST_NAME);
        $this->withLastName(self::LAST_NAME);
        $this->withBalance(self::BALANCE);
    }

    public static function create(): self
    {
        return new self();
    }

    public function withAccountNumber(string $accountNumber): self
    {
        $this->accountNumber = new AccountNumber($accountNumber);

        return $this;
    }

    public function withFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function withLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function withBalance(int $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function build(): BankAccount
    {
        return new BankAccount($this->accountNumber, $this->firstName, $this->lastName, $this->balance);
    }
}
