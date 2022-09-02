<?php

declare(strict_types=1);

namespace Tests\Usecase;

use App\Domain\Account\BankAccount;
use App\Domain\Account\BankAccountRepository;
use App\Domain\Exception\RepositoryException;
use App\Domain\Exception\RequestValidationException;
use App\Infrastructure\Fake\FakeBankAccountRepository;
use App\Usecase\WithdrawFunds\WithdrawFundsRequest;
use App\Usecase\WithdrawFunds\WithdrawFundsResponse;
use App\Usecase\WithdrawFunds\WithdrawFundsUseCase;
use PHPUnit\Framework\TestCase;

class WithdrawFundsUseCaseTest extends TestCase
{
    private WithdrawFundsUseCase $useCase;

    private BankAccountRepository $bankAccountRepository;

    protected function setUp(): void
    {
        $this->bankAccountRepository = new FakeBankAccountRepository();
        $this->useCase = new WithdrawFundsUseCase($this->bankAccountRepository);
    }

    public function testItWithdrawsFundsGivenValidRequest()
    {
        $accountNumber = 'Y665242';
        $initialBalance = 50;
        $bankAccount = new BankAccount($accountNumber, $initialBalance);
        $this->bankAccountRepository->add($bankAccount);

        $amount = 10;
        $request = new WithdrawFundsRequest($accountNumber, $amount);

        $expectedFinalBalance = 40;
        $expectedResponse = new WithdrawFundsResponse($expectedFinalBalance);

        $response = $this->useCase->__invoke($request);

        $this->assertEquals($expectedResponse, $response);

        $expectedBankAccount = new BankAccount($accountNumber, $expectedFinalBalance);
        $retrievedBankAccount = $this->bankAccountRepository->find($accountNumber);

        $this->assertEquals($expectedBankAccount, $retrievedBankAccount);
    }

    public function testItThrowExceptionGivenNonExistentAccountNumber(): void
    {
        $accountNumber = 'willNotBeFound';
        $request = new WithdrawFundsRequest($accountNumber, 10);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage(RepositoryException::BANK_ACCOUNT_NOT_FOUND_EXCEPTION_MESSAGE);

        $this->useCase->__invoke($request);
    }

    /**
     * @dataProvider provideEmptyValues
     */
    public function testItThrowExceptionGivenEmptyAccountNumber(string $accountNumber): void
    {
        $request = new WithdrawFundsRequest($accountNumber, 10);

        $this->expectException(RequestValidationException::class);
        $this->expectExceptionMessage(RequestValidationException::EMPTY_ACCOUNT_NUMBER);

        $this->useCase->__invoke($request);
    }

    /**
     * @dataProvider provideNonPositiveIntegers
     */
    public function testItThrowsExceptionGivenNonPositiveAmount(int $amount): void
    {
        FakeBankAccountRepository::reset();
        $accountNumber = 'Y665242';
        $bankAccount = new BankAccount($accountNumber, 100);
        $this->bankAccountRepository->add($bankAccount);

        $request = new WithdrawFundsRequest($accountNumber, $amount);

        $this->expectException(RequestValidationException::class);
        $this->expectExceptionMessage(RequestValidationException::NON_POSITIVE_TRANSACTION_AMOUNT);

        $this->useCase->__invoke($request);
    }

    private function provideNonPositiveIntegers(): array
    {
        return [[0], [-1], [-12]];
    }

    private function provideEmptyValues(): array
    {
        return [[''], [' '], ['   ']];
    }
}
