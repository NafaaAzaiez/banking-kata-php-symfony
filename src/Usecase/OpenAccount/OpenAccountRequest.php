<?php

declare(strict_types=1);

namespace App\Usecase\OpenAccount;

class OpenAccountRequest
{
    public string $firstName;

    public function __construct(string $firstName)
    {
        $this->firstName = $firstName;
    }
}
