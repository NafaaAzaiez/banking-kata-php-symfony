<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class RequestValidationException extends \InvalidArgumentException
{
    public const INVALID_FIRST_NAME = 'Firstname is empty';

    public static function withEmptyFirstName(): self
    {
        return new self(self::INVALID_FIRST_NAME);
    }
}
