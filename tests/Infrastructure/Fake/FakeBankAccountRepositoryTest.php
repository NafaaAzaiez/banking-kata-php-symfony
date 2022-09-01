<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Fake;

use App\Domain\Account\BankAccount;
use App\Domain\Account\BankAccountRepository;
use App\Infrastructure\Fake\FakeBankAccountRepository;
use PHPUnit\Framework\TestCase;

class FakeBankAccountRepositoryTest extends TestCase
{
    private FakeBankAccountRepository $bankAccountRepository;

    protected function setUp(): void
    {
        $this->bankAccountRepository = new FakeBankAccountRepository();
    }

    public function testItThrowsExceptionWhenBankAccountNotFound(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage(BankAccountRepository::BANK_ACCOUNT_NOT_FOUND_EXCEPTION_MESSAGE);

        $this->bankAccountRepository->find('whatever');
    }

    public function itReturnsBankAccountWhenItIsFound(): void
    {
        $accountNumber = 'X89799810';
        $bankAccount = new BankAccount($accountNumber, 100);
        $this->bankAccountRepository->add($bankAccount);
        $retrievedBankAccount = $this->bankAccountRepository->find($accountNumber);
        $this->assertSame($bankAccount, $retrievedBankAccount);
    }
}
