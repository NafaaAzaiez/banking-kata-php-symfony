<?php

declare(strict_types=1);

namespace App\Usecase\WithdrawFunds;

class WithdrawFundsUseCase
{
    public function __invoke(WithdrawFundsRequest $request): WithdrawFundsResponse
    {
        return new WithdrawFundsResponse();
    }
}
