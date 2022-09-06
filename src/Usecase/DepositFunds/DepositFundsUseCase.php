<?php

declare(strict_types=1);

namespace App\Usecase\DepositFunds;

class DepositFundsUseCase
{
    public function __invoke(DepositFundsRequest $request): DepositFundsResponse
    {
        return new DepositFundsResponse();
    }
}
