<?php

declare(strict_types=1);

namespace App\Usecase\OpenAccount;

use App\Domain\Exception\RequestValidationException;

class OpenAccountUseCase
{
    public function __invoke(OpenAccountRequest $request): OpenAccountResponse
    {
        if (empty(trim($request->firstName))) {
            throw RequestValidationException::withEmptyFirstName();
        }

        return new OpenAccountResponse();
    }
}
