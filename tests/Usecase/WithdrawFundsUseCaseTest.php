<?php

declare(strict_types=1);

namespace Tests\Usecase;

use App\Domain\Account\BankAccount;
use App\Domain\Account\BankAccountRepository;
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

    public function testItReturnsResponse()
    {
        $accountNumber = 'Y665242';
        $bankAccount = new BankAccount($accountNumber);
        $this->bankAccountRepository->add($bankAccount);

        $request = new WithdrawFundsRequest($accountNumber);
        $expectedResponse = new WithdrawFundsResponse();

        $response = $this->useCase->__invoke($request);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testItThrowExceptionGivenNonExistentAccountNumber(): void
    {
        $accountNumber = 'willNotBeFound';
        $request = new WithdrawFundsRequest($accountNumber);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage(BankAccountRepository::BANK_ACCOUNT_NOT_FOUND_EXCEPTION_MESSAGE);

        $this->useCase->__invoke($request);
    }

    /**
     * @dataProvider provideEmptyValues
     */
    public function testItThrowExceptionGivenEmptyAccountNumber(string $accountNumber): void
    {
        $request = new WithdrawFundsRequest($accountNumber);

        $this->expectException(RequestValidationException::class);
        $this->expectExceptionMessage(RequestValidationException::EMPTY_ACCOUNT_NUMBER);

        $this->useCase->__invoke($request);
    }

    private function provideEmptyValues(): array
    {
        return [[''], [' '], ['   ']];
    }
}
