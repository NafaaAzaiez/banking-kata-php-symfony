<?php

declare(strict_types=1);

namespace Tests\Usecase;

use App\Domain\Account\AccountNumber;
use App\Domain\Account\BankAccount;
use App\Domain\Account\BankAccountRepository;
use App\Domain\Exception\RepositoryException;
use App\Domain\Exception\RequestValidationException;
use App\Infrastructure\Fake\FakeBankAccountRepository;
use App\Usecase\WithdrawFunds\WithdrawFundsRequest;
use App\Usecase\WithdrawFunds\WithdrawFundsResponse;
use App\Usecase\WithdrawFunds\WithdrawFundsUseCase;
use PHPUnit\Framework\TestCase;
use Tests\Builder\Entity\BankAccountBuilder;

class WithdrawFundsUseCaseTest extends TestCase
{
    private WithdrawFundsUseCase $useCase;

    private BankAccountRepository $bankAccountRepository;

    protected function setUp(): void
    {
        $this->bankAccountRepository = new FakeBankAccountRepository();
        $this->useCase = new WithdrawFundsUseCase($this->bankAccountRepository);
    }

    protected function tearDown(): void
    {
        FakeBankAccountRepository::reset();
    }

    public function testItWithdrawsFundsGivenValidRequest(): void
    {
        $accountNumber = 'Y665242';
        $initialBalance = 50;
        $amount = 10;
        $expectedFinalBalance = 40;

        $this->givenBankAccount($accountNumber, $initialBalance);

        $response = $this->withdrawFunds($accountNumber, $amount);

        $this->assertExpectedResponse($response, $expectedFinalBalance);
        $this->assertContainsBankAccount($accountNumber, $expectedFinalBalance);
    }

    public function testItThrowExceptionGivenNonExistentAccountNumber(): void
    {
        $accountNumber = 'willNotBeFound';

        $this->expectExceptionWithMessage(RepositoryException::class, RepositoryException::BANK_ACCOUNT_NOT_FOUND_EXCEPTION_MESSAGE);

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

        $this->givenBankAccount($accountNumber, $initialBalance);

        $this->expectExceptionWithMessage(RequestValidationException::class, RequestValidationException::INSUFFICIENT_FUNDS);

        $this->withdrawFunds($accountNumber, $amount);
    }

    /**
     * @phpstan-ignore-next-line
     */
    private function provideNonPositiveIntegers(): array
    {
        return [[0], [-1], [-12]];
    }

    /**
     * @phpstan-ignore-next-line
     */
    private function provideEmptyValues(): array
    {
        return [[''], [' '], ['   ']];
    }

    private function find(string $accountNumber): BankAccount
    {
        return $this->bankAccountRepository->find(new AccountNumber($accountNumber));
    }

    private function givenBankAccount(string $accountNumber, int $balance): void
    {
        $bankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->withBalance($balance)
            ->build()
        ;
        $this->bankAccountRepository->add($bankAccount);
    }

    private function assertContainsBankAccount(string $accountNumber, int $expectedBalance): void
    {
        $expectedBankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->withBalance($expectedBalance)
            ->build()
        ;
        $retrievedBankAccount = $this->find($accountNumber);

        $this->assertEquals($expectedBankAccount, $retrievedBankAccount);
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

    private function expectExceptionWithMessage(string $exceptionClass, string $exceptionMessage): void
    {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);
    }
}
