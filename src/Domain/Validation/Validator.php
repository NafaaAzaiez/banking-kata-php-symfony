<?php

declare(strict_types=1);

namespace App\Domain\Validation;

use App\Domain\Exception\RequestValidationException;

class Validator
{
    public static function assertNotBlank(string $value, string $errorMessage): void
    {
        if (empty(trim($value))) {
            throw RequestValidationException::withMessage($errorMessage);
        }
    }

    public static function assertNotNegative(int $value, string $errorMessage): void
    {
        if ($value < 0) {
            throw RequestValidationException::withMessage($errorMessage);
        }
    }
}
