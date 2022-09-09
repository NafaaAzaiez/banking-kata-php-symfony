<?php

declare(strict_types=1);

namespace Tests;

use App\Domain\Account\BankAccount;
use App\Domain\Account\BankAccountRepository;
use PHPUnit\Framework\TestCase;

abstract class AbstractBankingTestCase extends TestCase
{
    protected BankAccountRepository $bankAccountRepository;

    protected function expectExceptionWithMessage(string $exceptionClass, string $exceptionMessage): void
    {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);
    }

    protected function givenBankAccount(BankAccount $bankAccount): void
    {
        $this->bankAccountRepository->add($bankAccount);
    }

    protected function assertContainsBankAccount(BankAccount $expectedBankAccount): void
    {
        $retrievedBankAccount = $this->bankAccountRepository->find($expectedBankAccount->getAccountNumber());

        $this->assertEquals($expectedBankAccount, $retrievedBankAccount);
    }

    /**
     * @phpstan-ignore-next-line
     */
    private function provideEmptyValues(): array
    {
        return [[''], [' '], ['   ']];
    }

    /**
     * @phpstan-ignore-next-line
     */
    private function provideNonStrictPositiveIntegers(): array
    {
        return [[0], [-1], [-12]];
    }
}
