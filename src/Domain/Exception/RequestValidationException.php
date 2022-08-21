<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class RequestValidationException extends \InvalidArgumentException
{
    public const INVALID_FIRST_NAME = 'Firstname is empty';

    public const INVALID_LAST_NAME = 'Last name is empty';

    public static function withMessage(string $message): self
    {
        return new self($message);
    }
}
