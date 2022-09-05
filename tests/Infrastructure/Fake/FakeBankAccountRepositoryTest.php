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
    private const BALANCE = 100;

    private const FIRSTNAME = 'John';

    private const LASTNAME = 'DOE';

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
        $this->expectExceptionWithMessage(RepositoryException::class, RepositoryException::BANK_ACCOUNT_NOT_FOUND_EXCEPTION_MESSAGE);

        $this->find('whatever');
    }

    public function itReturnsBankAccountWhenItIsFound(): void
    {
        $accountNumber = 'X4433122';

        $this->givenBankAccount($accountNumber, self::FIRSTNAME, self::LASTNAME, self::BALANCE);

        $this->assertContainsBankAccount($accountNumber, self::FIRSTNAME, self::LASTNAME, self::BALANCE);
    }

    public function testItShouldNotBeAbleToChangeBankAccountAfterAdd(): void
    {
        $accountNumber = 'X89799810';
        $bankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->withFirstName(self::FIRSTNAME)
            ->withLastName(self::LASTNAME)
            ->withBalance(self::BALANCE)
            ->build()
        ;

        $this->bankAccountRepository->add($bankAccount);

        $bankAccount->setBalance(500);

        $this->assertContainsBankAccount($accountNumber, self::FIRSTNAME, self::LASTNAME, self::BALANCE);
    }

    public function testItShouldNotBeAbleToChangeBankAccountAfterFind(): void
    {
        $accountNumber = 'X89799810';

        $this->givenBankAccount($accountNumber, self::FIRSTNAME, self::LASTNAME, self::BALANCE);

        $retrievedBankAccount = $this->find($accountNumber);
        $retrievedBankAccount->setBalance(500);

        $this->assertContainsBankAccount($accountNumber, self::FIRSTNAME, self::LASTNAME, self::BALANCE);
    }

    public function testItShouldNotBeAbleToChangeBankAccountAfterUpdate(): void
    {
        $accountNumber = 'X89799810';

        $this->givenBankAccount($accountNumber, self::FIRSTNAME, self::LASTNAME, self::BALANCE);

        $retrievedBankAccount = $this->find($accountNumber);
        $this->bankAccountRepository->update($retrievedBankAccount);
        $retrievedBankAccount->setBalance(500);

        $this->assertContainsBankAccount($accountNumber, self::FIRSTNAME, self::LASTNAME, self::BALANCE);
    }

    public function testItShouldRetrieveUpdatedBankAccountAfterUpdate(): void
    {
        $accountNumber = 'X89799810';
        $expectedBalance = 50;

        $this->givenBankAccount($accountNumber, self::FIRSTNAME, self::LASTNAME, self::BALANCE);

        $retrievedBankAccount = $this->find($accountNumber);
        $retrievedBankAccount->setBalance(50);
        $this->bankAccountRepository->update($retrievedBankAccount);

        $this->assertContainsBankAccount($accountNumber, self::FIRSTNAME, self::LASTNAME, $expectedBalance);
    }

    public function shouldThrowExceptionWhenAttemptAddBankAccountWithSameAccountNumberTwice(): void
    {
        $accountNumber = 'X89799810';

        $this->givenBankAccount($accountNumber, self::FIRSTNAME, self::LASTNAME, self::BALANCE);

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
