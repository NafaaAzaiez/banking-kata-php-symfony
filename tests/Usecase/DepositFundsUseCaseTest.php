<?php

declare(strict_types=1);

namespace Tests\Usecase;

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
        $request = new DepositFundsRequest();
        $expectedResponse = new DepositFundsResponse();
        $response = $this->useCase->__invoke($request);

        $this->assertEquals($expectedResponse, $response);
    }
}
