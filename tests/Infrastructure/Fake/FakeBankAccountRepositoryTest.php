<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Fake;

use App\Domain\Account\BankAccount;
use App\Infrastructure\Fake\FakeBankAccountRepository;
use PHPUnit\Framework\TestCase;

class FakeBankAccountRepositoryTest extends TestCase
{
    private FakeBankAccountRepository $bankAccountRepository;

    protected function setUp(): void
    {
        $this->bankAccountRepository = new FakeBankAccountRepository();
    }

    public function testItThrowsExceptionWhenBankAccountNotFound()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage(FakeBankAccountRepository::BANK_ACCOUNT_NOT_FOUND_EXCEPTION_MESSAGE);

        $this->bankAccountRepository->find('whatever');
    }

    public function itReturnsBankAccountWhenItIsFound()
    {
        $accountNumber = 'X89799810';
        $bankAccount = new BankAccount($accountNumber, 100);
        $this->bankAccountRepository->add($bankAccount);
        $this->assertSame($bankAccount, $this->bankAccountRepository->find($accountNumber));
    }
}
