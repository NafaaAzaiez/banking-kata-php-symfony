<?php

declare(strict_types=1);

namespace Tests\Usecase;

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
        $request = new WithdrawFundsRequest();
        $expectedResponse = new WithdrawFundsResponse();

        $response = $this->useCase->__invoke($request);

        $this->assertEquals($expectedResponse, $response);
    }
}
