<?php

declare(strict_types=1);

namespace Tests\Usecase;

use App\Domain\Exception\RequestValidationException;
use App\Usecase\WithdrawFunds\WithdrawFundsRequest;
use App\Usecase\WithdrawFunds\WithdrawFundsResponse;
use App\Usecase\WithdrawFunds\WithdrawFundsUseCase;
use PHPUnit\Framework\TestCase;

class WithdrawFundsUseCaseTest extends TestCase
{
    private WithdrawFundsUseCase $useCase;

    protected function setUp(): void
    {
        $this->useCase = new WithdrawFundsUseCase();
    }

    public function testItReturnsResponse()
    {
        $request = new WithdrawFundsRequest('randomAccountNumber');
        $expectedResponse = new WithdrawFundsResponse();

        $response = $this->useCase->__invoke($request);

        $this->assertEquals($expectedResponse, $response);
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
