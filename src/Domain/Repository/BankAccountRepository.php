<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\BankAccount;

interface BankAccountRepository
{
    public function find(string $accountNumber): BankAccount;

    public function add(BankAccount $bankAccount): void;
}
