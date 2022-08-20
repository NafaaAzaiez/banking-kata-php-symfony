<?php

declare(strict_types=1);

namespace App\Usecase\OpenAccount;

class OpenAccountUseCase
{
    public function __invoke(OpenAccountRequest $request): OpenAccountResponse
    {
        return new OpenAccountResponse();
    }
}
