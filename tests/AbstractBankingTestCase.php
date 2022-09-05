<?php

declare(strict_types=1);

namespace Tests;

use App\Domain\Account\AccountNumber;
use App\Domain\Account\BankAccount;
use App\Domain\Account\BankAccountRepository;
use PHPUnit\Framework\TestCase;
use Tests\Builder\Entity\BankAccountBuilder;

abstract class AbstractBankingTestCase extends TestCase
{
    protected BankAccountRepository $bankAccountRepository;

    protected function expectExceptionWithMessage(string $exceptionClass, string $exceptionMessage): void
    {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);
    }

    protected function givenBankAccount(string $accountNumber, string $firstName, string $lastName, int $balance): void
    {
        $bankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->withFirstName($firstName)
            ->withLastName($lastName)
            ->withBalance($balance)
            ->build()
        ;
        $this->bankAccountRepository->add($bankAccount);
    }

    protected function assertContainsBankAccount(string $accountNumber, string $firstName, string $lastName, int $expectedBalance): void
    {
        $expectedBankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->withFirstName($firstName)
            ->withLastName($lastName)
            ->withBalance($expectedBalance)
            ->build()
        ;
        $retrievedBankAccount = $this->findBankAccount($accountNumber);

        $this->assertEquals($expectedBankAccount, $retrievedBankAccount);
    }

    protected function findBankAccount(string $accountNumber): BankAccount
    {
        return $this->bankAccountRepository->find(new AccountNumber($accountNumber));
    }
}
