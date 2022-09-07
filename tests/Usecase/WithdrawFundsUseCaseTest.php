<?php

declare(strict_types=1);

namespace Tests\Usecase;

use App\Domain\Exception\RepositoryException;
use App\Domain\Exception\RequestValidationException;
use App\Infrastructure\Fake\FakeBankAccountRepository;
use App\Usecase\WithdrawFunds\WithdrawFundsRequest;
use App\Usecase\WithdrawFunds\WithdrawFundsResponse;
use App\Usecase\WithdrawFunds\WithdrawFundsUseCase;
use Tests\AbstractBankingTestCase;
use Tests\Builder\Entity\BankAccountBuilder;

class WithdrawFundsUseCaseTest extends AbstractBankingTestCase
{
    private WithdrawFundsUseCase $useCase;

    protected function setUp(): void
    {
        $this->bankAccountRepository = new FakeBankAccountRepository();
        $this->useCase = new WithdrawFundsUseCase($this->bankAccountRepository);
        FakeBankAccountRepository::reset();
    }

    /**
     * @dataProvider provideDataForValidWithdrawalRequest
     */
    public function testItWithdrawsFundsGivenValidRequest(string $accountNumber, int $initialBalance, int $amount, int $expectedFinalBalance): void
    {
        $bankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->withBalance($initialBalance)
            ->build()
        ;

        $this->givenBankAccount($bankAccount);

        $response = $this->withdrawFunds($accountNumber, $amount);

        $this->assertExpectedResponse($response, $expectedFinalBalance);

        $expectedBankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->withBalance($expectedFinalBalance)
            ->build()
        ;
        $this->assertContainsBankAccount($expectedBankAccount);
    }

    public function testItThrowExceptionGivenNonExistentAccountNumber(): void
    {
        $accountNumber = 'willNotBeFound';

        $this->expectExceptionWithMessage(RepositoryException::class, RepositoryException::BANK_ACCOUNT_NOT_FOUND);

        $this->withdrawFunds($accountNumber, 10);
    }

    /**
     * @dataProvider provideEmptyValues
     */
    public function testItThrowExceptionGivenEmptyAccountNumber(string $accountNumber): void
    {
        $this->expectExceptionWithMessage(RequestValidationException::class, RequestValidationException::EMPTY_ACCOUNT_NUMBER);

        $this->withdrawFunds($accountNumber, 10);
    }

    /**
     * @dataProvider provideNonPositiveIntegers
     */
    public function testItThrowsExceptionGivenNonPositiveAmount(int $amount): void
    {
        $accountNumber = 'Y665242';

        $this->expectExceptionWithMessage(RequestValidationException::class, RequestValidationException::NON_POSITIVE_TRANSACTION_AMOUNT);

        $this->withdrawFunds($accountNumber, $amount);
    }

    public function testItThrowExceptionGivenInsufficientFunds(): void
    {
        $accountNumber = 'Y665242';
        $initialBalance = 50;
        $amount = 150;

        $bankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->withBalance($initialBalance)
            ->build()
        ;

        $this->givenBankAccount($bankAccount);

        $this->expectExceptionWithMessage(RequestValidationException::class, RequestValidationException::INSUFFICIENT_FUNDS);

        $this->withdrawFunds($accountNumber, $amount);
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function provideDataForValidWithdrawalRequest(): array
    {
        return [
            ['A001', 50, 20, 30],
            ['B002', 100, 100, 0],
        ];
    }

    /**
     * @phpstan-ignore-next-line
     */
    private function provideNonPositiveIntegers(): array
    {
        return [[0], [-1], [-12]];
    }

    private function withdrawFunds(string $accountNumber, int $amount): WithdrawFundsResponse
    {
        $request = new WithdrawFundsRequest($accountNumber, $amount);

        return $this->useCase->__invoke($request);
    }

    private function assertExpectedResponse(WithdrawFundsResponse $response, int $expectedBalance): void
    {
        $expectedResponse = new WithdrawFundsResponse($expectedBalance);
        $this->assertEquals($expectedResponse, $response);
    }
}
