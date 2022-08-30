<?php

declare(strict_types=1);

namespace App\Usecase\WithdrawFunds;

use App\Domain\Exception\RequestValidationException;
use App\Domain\Validation\Validator;

class WithdrawFundsUseCase
{
    public function __invoke(WithdrawFundsRequest $request): WithdrawFundsResponse
    {
        Validator::assertNotBlank($request->accountNumber, RequestValidationException::EMPTY_ACCOUNT_NUMBER);

        return new WithdrawFundsResponse();
    }
}
