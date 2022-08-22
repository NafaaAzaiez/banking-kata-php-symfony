<?php

declare(strict_types=1);

namespace App\Domain\Generators;

interface AccountNumberGenerator
{
    public function generate(): string;
}
