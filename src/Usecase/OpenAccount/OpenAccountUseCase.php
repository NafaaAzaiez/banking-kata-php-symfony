<?php

declare(strict_types=1);

namespace App\Usecase\OpenAccount;

use App\Domain\Exception\RequestValidationException;
use App\Domain\Generators\AccountNumberGenerator;
use App\Domain\Validation\Validator;

class OpenAccountUseCase
{
    private AccountNumberGenerator $accountNumberGenerator;

    public function __construct(AccountNumberGenerator $accountNumberGenerator)
    {
        $this->accountNumberGenerator = $accountNumberGenerator;
    }

    public function __invoke(OpenAccountRequest $request): OpenAccountResponse
    {
        Validator::assertNotBlank($request->firstName, RequestValidationException::INVALID_FIRST_NAME);
        Validator::assertNotBlank($request->lastName, RequestValidationException::INVALID_LAST_NAME);
        Validator::assertNotNegative($request->initialBalance, RequestValidationException::INITIAL_BALANCE_NEGATIVE);

        $accountNumber = $this->accountNumberGenerator->generate();

        return new OpenAccountResponse($accountNumber);
    }
}
