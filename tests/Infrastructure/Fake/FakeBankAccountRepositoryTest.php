<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Fake;

use App\Domain\Account\BankAccount;
use App\Domain\Exception\RepositoryException;
use App\Infrastructure\Fake\FakeBankAccountRepository;
use PHPUnit\Framework\TestCase;

class FakeBankAccountRepositoryTest extends TestCase
{
    private FakeBankAccountRepository $bankAccountRepository;

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
        $this->expectException(RepositoryException::class);
        $this->expectExceptionMessage(RepositoryException::BANK_ACCOUNT_NOT_FOUND_EXCEPTION_MESSAGE);

        $this->bankAccountRepository->find('whatever');
    }

    public function itReturnsBankAccountWhenItIsFound(): void
    {
        $accountNumber = 'X89799810';
        $bankAccount = new BankAccount($accountNumber, 100);
        $this->bankAccountRepository->add($bankAccount);
        $retrievedBankAccount = $this->bankAccountRepository->find($accountNumber);
        $this->assertEquals($bankAccount, $retrievedBankAccount);
    }

    public function testItShouldNotBeAbleToChangeBankAccountAfterAdd(): void
    {
        $accountNumber = 'X89799810';
        $bankAccount = new BankAccount($accountNumber, 100);
        $expectedBankAccount = new BankAccount($accountNumber, 100);

        $this->bankAccountRepository->add($bankAccount);

        $bankAccount->setBalance(500);

        $retrievedBankAccount = $this->bankAccountRepository->find($accountNumber);

        $this->assertEquals($expectedBankAccount, $retrievedBankAccount);
    }

    public function testItShouldNotBeAbleToChangeBankAccountAfterFind(): void
    {
        $accountNumber = 'X89799810';
        $bankAccount = new BankAccount($accountNumber, 100);
        $expectedBankAccount = new BankAccount($accountNumber, 100);

        $this->bankAccountRepository->add($bankAccount);

        $retrievedBankAccount = $this->bankAccountRepository->find($accountNumber);
        $retrievedBankAccount->setBalance(500);

        $retrievedBankAccountAfterModification = $this->bankAccountRepository->find($accountNumber);

        $this->assertEquals($expectedBankAccount, $retrievedBankAccountAfterModification);
    }

    public function testItShouldNotBeAbleToChangeBankAccountAfterUpdate(): void
    {
        $accountNumber = 'X89799810';
        $initialBalance = 100;
        $bankAccount = new BankAccount($accountNumber, $initialBalance);
        $expectedBankAccount = new BankAccount($accountNumber, $initialBalance);

        $this->bankAccountRepository->add($bankAccount);

        $retrievedBankAccount = $this->bankAccountRepository->find($accountNumber);
        $this->bankAccountRepository->update($retrievedBankAccount);
        $retrievedBankAccount->setBalance(500);

        $retrievedBankAccountAfterUpdate = $this->bankAccountRepository->find($accountNumber);

        $this->assertEquals($expectedBankAccount, $retrievedBankAccountAfterUpdate);
    }

    public function testItShouldRetrieveUpdatedBankAccountAfterUpdate(): void
    {
        $accountNumber = 'X89799810';
        $initialBalance = 100;
        $expectedBalance = 50;
        $bankAccount = new BankAccount($accountNumber, $initialBalance);
        $expectedBankAccount = new BankAccount($accountNumber, $expectedBalance);

        $this->bankAccountRepository->add($bankAccount);

        $retrievedBankAccount = $this->bankAccountRepository->find($accountNumber);
        $retrievedBankAccount->setBalance(50);
        $this->bankAccountRepository->update($retrievedBankAccount);

        $retrievedBankAccountAfterUpdate = $this->bankAccountRepository->find($accountNumber);

        $this->assertEquals($expectedBankAccount, $retrievedBankAccountAfterUpdate);
    }

    public function shouldThrowExceptionWhenAttemptAddBankAccountWithSameAccountNumberTwice(): void
    {
        $accountNumber = 'X89799810';
        $bankAccount = new BankAccount($accountNumber, 100);

        $this->bankAccountRepository->add($bankAccount);

        $this->expectException(RepositoryException::class);
        $this->expectExceptionMessage(RepositoryException::BANK_ACCOUNT_ALREADY_EXISTS);
        $this->bankAccountRepository->add($bankAccount);
    }
}
