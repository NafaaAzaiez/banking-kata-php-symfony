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

        $response = $this->depositFunds($accountNumber);

        $expectedResponse = new DepositFundsResponse();
        $this->assertEquals($expectedResponse, $response);
    }

    public function testItThrowExceptionGivenNonExistentAccountNumber(): void
    {
        $accountNumber = 'wontBeFound';

        $this->expectExceptionWithMessage(RepositoryException::class, RepositoryException::BANK_ACCOUNT_NOT_FOUND);
        $this->depositFunds($accountNumber);
    }

    /**
     * @dataProvider provideEmptyValues
     */
    public function testItThrowExceptionGivenEmptyAccountNumber(string $accountNumber): void
    {
        $this->expectExceptionWithMessage(RequestValidationException::class, RequestValidationException::EMPTY_ACCOUNT_NUMBER);

        $this->depositFunds($accountNumber);
    }

    private function depositFunds(string $accountNumber): DepositFundsResponse
    {
        $request = new DepositFundsRequest($accountNumber);

        return $this->useCase->__invoke($request);
    }
}
