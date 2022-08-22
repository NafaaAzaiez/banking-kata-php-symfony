<?php

declare(strict_types=1);

namespace App\Usecase\OpenAccount;

class OpenAccountResponse
{
    public string $accountNumber;

    public function __construct(string $accountNumber)
    {
        $this->accountNumber = $accountNumber;
    }
}
