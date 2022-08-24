<?php

declare(strict_types=1);

namespace App\Domain\Account;

interface AccountNumberGenerator
{
    public function generate(): string;
}
