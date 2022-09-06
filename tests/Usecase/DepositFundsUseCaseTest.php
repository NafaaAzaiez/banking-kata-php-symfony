<?php

declare(strict_types=1);

namespace Tests\Usecase;

use App\Domain\Exception\RequestValidationException;
use App\Usecase\DepositFunds\DepositFundsRequest;
use App\Usecase\DepositFunds\DepositFundsResponse;
use App\Usecase\DepositFunds\DepositFundsUseCase;
use Tests\AbstractBankingTestCase;

class DepositFundsUseCaseTest extends AbstractBankingTestCase
{
    private DepositFundsUseCase $useCase;

    protected function setUp(): void
    {
        $this->useCase = new DepositFundsUseCase();
    }

    public function testItShouldDepositFundsGivenValidRequest(): void
    {
        $accountNumber = 'Y998771';
        $expectedResponse = new DepositFundsResponse();
        $response = $this->depositFunds($accountNumber);

        $this->assertEquals($expectedResponse, $response);
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
