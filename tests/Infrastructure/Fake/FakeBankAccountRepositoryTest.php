<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Fake;

use App\Domain\Account\AccountNumber;
use App\Domain\Account\BankAccount;
use App\Domain\Exception\RepositoryException;
use App\Infrastructure\Fake\FakeBankAccountRepository;
use PHPUnit\Framework\TestCase;
use Tests\Common\Factory;

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

        $this->find('whatever');
    }

    public function itReturnsBankAccountWhenItIsFound(): void
    {
        $accountNumber = 'X89799810';
        $bankAccount = Factory::createDefaultBankAccount($accountNumber);
        $this->bankAccountRepository->add($bankAccount);
        $retrievedBankAccount = $this->find($accountNumber);
        $this->assertEquals($bankAccount, $retrievedBankAccount);
    }

    public function testItShouldNotBeAbleToChangeBankAccountAfterAdd(): void
    {
        $accountNumber = 'X89799810';
        $bankAccount = Factory::createDefaultBankAccount($accountNumber);
        $expectedBankAccount = Factory::createDefaultBankAccount($accountNumber);

        $this->bankAccountRepository->add($bankAccount);

        $bankAccount->setBalance(500);

        $retrievedBankAccount = $this->find($accountNumber);

        $this->assertEquals($expectedBankAccount, $retrievedBankAccount);
    }

    public function testItShouldNotBeAbleToChangeBankAccountAfterFind(): void
    {
        $accountNumber = 'X89799810';
        $bankAccount = Factory::createDefaultBankAccount($accountNumber);
        $expectedBankAccount = Factory::createDefaultBankAccount($accountNumber);

        $this->bankAccountRepository->add($bankAccount);

        $retrievedBankAccount = $this->find($accountNumber);
        $retrievedBankAccount->setBalance(500);

        $retrievedBankAccountAfterModification = $this->find($accountNumber);

        $this->assertEquals($expectedBankAccount, $retrievedBankAccountAfterModification);
    }

    public function testItShouldNotBeAbleToChangeBankAccountAfterUpdate(): void
    {
        $accountNumber = 'X89799810';
        $initialBalance = 100;
        $bankAccount = Factory::createDefaultBankAccount($accountNumber, $initialBalance);
        $expectedBankAccount = Factory::createDefaultBankAccount($accountNumber, $initialBalance);

        $this->bankAccountRepository->add($bankAccount);

        $retrievedBankAccount = $this->find($accountNumber);
        $this->bankAccountRepository->update($retrievedBankAccount);
        $retrievedBankAccount->setBalance(500);

        $retrievedBankAccountAfterUpdate = $this->find($accountNumber);

        $this->assertEquals($expectedBankAccount, $retrievedBankAccountAfterUpdate);
    }

    public function testItShouldRetrieveUpdatedBankAccountAfterUpdate(): void
    {
        $accountNumber = 'X89799810';
        $initialBalance = 100;
        $expectedBalance = 50;
        $bankAccount = Factory::createDefaultBankAccount($accountNumber, $initialBalance);
        $expectedBankAccount = Factory::createDefaultBankAccount($accountNumber, $expectedBalance);

        $this->bankAccountRepository->add($bankAccount);

        $retrievedBankAccount = $this->find($accountNumber);
        $retrievedBankAccount->setBalance(50);
        $this->bankAccountRepository->update($retrievedBankAccount);

        $retrievedBankAccountAfterUpdate = $this->find($accountNumber);

        $this->assertEquals($expectedBankAccount, $retrievedBankAccountAfterUpdate);
    }

    public function shouldThrowExceptionWhenAttemptAddBankAccountWithSameAccountNumberTwice(): void
    {
        $accountNumber = 'X89799810';
        $bankAccount = Factory::createDefaultBankAccount($accountNumber);

        $this->bankAccountRepository->add($bankAccount);

        $this->expectException(RepositoryException::class);
        $this->expectExceptionMessage(RepositoryException::BANK_ACCOUNT_ALREADY_EXISTS);
        $this->bankAccountRepository->add($bankAccount);
    }

    private function find(string $accountNumber): BankAccount
    {
        return $this->bankAccountRepository->find(new AccountNumber($accountNumber));
    }
}
