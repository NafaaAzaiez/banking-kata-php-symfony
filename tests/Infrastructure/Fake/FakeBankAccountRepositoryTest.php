<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Fake;

use App\Domain\Account\AccountNumber;
use App\Domain\Account\BankAccount;
use App\Domain\Exception\RepositoryException;
use App\Infrastructure\Fake\FakeBankAccountRepository;
use Tests\AbstractBankingTestCase;
use Tests\Builder\Entity\BankAccountBuilder;

class FakeBankAccountRepositoryTest extends AbstractBankingTestCase
{
    protected function setUp(): void
    {
        $this->bankAccountRepository = new FakeBankAccountRepository();
    }

    protected function tearDown(): void
    {
        FakeBankAccountRepository::reset();
    }

    public function testItThrowsExceptionWhenBankAccountNotFound(): void
    {
        $this->expectExceptionWithMessage(RepositoryException::class, RepositoryException::BANK_ACCOUNT_NOT_FOUND);

        $this->find('whatever');
    }

    public function itReturnsBankAccountWhenItIsFound(): void
    {
        $accountNumber = 'X4433122';
        $bankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->build()
        ;

        $this->givenBankAccount($bankAccount);

        $expectedBankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->build()
        ;

        $this->assertContainsBankAccount($expectedBankAccount);
    }

    public function testItShouldNotBeAbleToChangeBankAccountAfterAdd(): void
    {
        $accountNumber = 'X89799810';
        $initialBalance = 100;
        $bankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->withBalance($initialBalance)
            ->build()
        ;

        $this->givenBankAccount($bankAccount);

        $bankAccount->deposit(500);

        $expectedBankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->withBalance($initialBalance)
            ->build()
        ;
        $this->assertContainsBankAccount($expectedBankAccount);
    }

    public function testItShouldNotBeAbleToChangeBankAccountAfterFind(): void
    {
        $accountNumber = 'X89799810';
        $initialBalance = 100;
        $bankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->withBalance($initialBalance)
            ->build()
        ;
        $this->givenBankAccount($bankAccount);

        $retrievedBankAccount = $this->find($accountNumber);
        $retrievedBankAccount->deposit(500);

        $expectedBankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->withBalance($initialBalance)
            ->build()
        ;
        $this->assertContainsBankAccount($expectedBankAccount);
    }

    public function testItShouldNotBeAbleToChangeBankAccountAfterUpdate(): void
    {
        $accountNumber = 'X89799810';
        $initialBalance = 100;
        $bankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->withBalance($initialBalance)
            ->build()
        ;
        $this->givenBankAccount($bankAccount);

        $retrievedBankAccount = $this->find($accountNumber);
        $this->bankAccountRepository->update($retrievedBankAccount);
        $retrievedBankAccount->deposit(500);

        $expectedBankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->withBalance($initialBalance)
            ->build()
        ;
        $this->assertContainsBankAccount($expectedBankAccount);
    }

    public function testItShouldRetrieveUpdatedBankAccountAfterUpdate(): void
    {
        $accountNumber = 'X89799810';
        $initialBalance = 100;
        $expectedBalance = 50;

        $bankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->withBalance($initialBalance)
            ->build()
        ;
        $this->givenBankAccount($bankAccount);

        $retrievedBankAccount = $this->find($accountNumber);
        $retrievedBankAccount->withdraw(50);
        $this->bankAccountRepository->update($retrievedBankAccount);

        $expectedBankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->withBalance($expectedBalance)
            ->build()
        ;
        $this->assertContainsBankAccount($expectedBankAccount);
    }

    public function shouldThrowExceptionWhenAttemptAddBankAccountWithSameAccountNumberTwice(): void
    {
        $accountNumber = 'X89799810';

        $bankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->build()
        ;
        $this->givenBankAccount($bankAccount);

        $anotherBankAccountWithTheSameNumber = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->build()
        ;

        $this->expectExceptionWithMessage(RepositoryException::class, RepositoryException::BANK_ACCOUNT_ALREADY_EXISTS);

        $this->bankAccountRepository->add($anotherBankAccountWithTheSameNumber);
    }

    private function find(string $accountNumber): BankAccount
    {
        return $this->bankAccountRepository->find(new AccountNumber($accountNumber));
    }
}
