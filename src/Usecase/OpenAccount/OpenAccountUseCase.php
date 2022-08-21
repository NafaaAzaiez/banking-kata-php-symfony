<?php

declare(strict_types=1);

namespace App\Usecase\OpenAccount;

use App\Domain\Exception\RequestValidationException;
use App\Domain\Validation\Validator;

class OpenAccountUseCase
{
    public function __invoke(OpenAccountRequest $request): OpenAccountResponse
    {
        Validator::assertNotBlank($request->firstName, RequestValidationException::INVALID_FIRST_NAME);
        Validator::assertNotBlank($request->lastName, RequestValidationException::INVALID_LAST_NAME);

        return new OpenAccountResponse();
    }
}
