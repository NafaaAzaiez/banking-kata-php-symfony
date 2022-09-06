<?php

declare(strict_types=1);

namespace App\Usecase\DepositFunds;

use App\Domain\Exception\RequestValidationException;
use App\Domain\Validation\Validator;

class DepositFundsUseCase
{
    public function __invoke(DepositFundsRequest $request): DepositFundsResponse
    {
        Validator::assertNotBlank($request->accountNumber, RequestValidationException::EMPTY_ACCOUNT_NUMBER);

        return new DepositFundsResponse();
    }
}
