<?php

declare(strict_types=1);

namespace App\Usecase\OpenAccount;

class OpenAccountRequest
{
    public string $firstName;

    public string $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}
