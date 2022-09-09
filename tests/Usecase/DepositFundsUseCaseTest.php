<?php

declare(strict_types=1);

namespace Tests\Usecase;

use App\Domain\Exception\RepositoryException;
use App\Domain\Exception\RequestValidationException;
use App\Infrastructure\Fake\FakeBankAccountRepository;
use App\Usecase\DepositFunds\DepositFundsRequest;
use App\Usecase\DepositFunds\DepositFundsResponse;
use App\Usecase\DepositFunds\DepositFundsUseCase;
use Tests\AbstractBankingTestCase;
use Tests\Builder\Entity\BankAccountBuilder;

class DepositFundsUseCaseTest extends AbstractBankingTestCase
{
    private DepositFundsUseCase $useCase;

    protected function setUp(): void
    {
        $this->bankAccountRepository = new FakeBankAccountRepository();
        $this->useCase = new DepositFundsUseCase($this->bankAccountRepository);
    }

    public function testItShouldDepositFundsGivenValidRequest(): void
    {
        $accountNumber = 'Y998771';

        $bankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->build()
        ;
        $this->givenBankAccount($bankAccount);

        $response = $this->depositFunds($accountNumber, 10);

        $expectedResponse = new DepositFundsResponse();
        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @dataProvider provideNonStrictPositiveIntegers
     */
    public function testItThrowExceptionGivenNonPositiveAmount(int $amount): void
    {
        $accountNumber = 'Y998771';

        $this->expectExceptionWithMessage(RequestValidationException::class, RequestValidationException::NON_POSITIVE_TRANSACTION_AMOUNT);
        $this->depositFunds($accountNumber, $amount);
    }

    public function testItThrowExceptionGivenNonExistentAccountNumber(): void
    {
        $accountNumber = 'wontBeFound';

        $this->expectExceptionWithMessage(RepositoryException::class, RepositoryException::BANK_ACCOUNT_NOT_FOUND);
        $this->depositFunds($accountNumber, 10);
    }

    /**
     * @dataProvider provideEmptyValues
     */
    public function testItThrowExceptionGivenEmptyAccountNumber(string $accountNumber): void
    {
        $this->expectExceptionWithMessage(RequestValidationException::class, RequestValidationException::EMPTY_ACCOUNT_NUMBER);

        $this->depositFunds($accountNumber, 10);
    }

    private function depositFunds(string $accountNumber, int $amount): DepositFundsResponse
    {
        $request = new DepositFundsRequest($accountNumber, $amount);

        return $this->useCase->__invoke($request);
    }
}
