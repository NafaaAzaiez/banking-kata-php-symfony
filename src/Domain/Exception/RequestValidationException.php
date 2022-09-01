<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class RequestValidationException extends \InvalidArgumentException
{
    public const INVALID_FIRST_NAME = 'Firstname is empty';

    public const INVALID_LAST_NAME = 'Last name is empty';

    public const INITIAL_BALANCE_NEGATIVE = 'Initial balance is negative';

    public const EMPTY_ACCOUNT_NUMBER = 'Account number is empty';

    public const ACCOUNT_NUMBER_NOT_FOUND = 'Account number not found';

    public const NON_POSITIVE_TRANSACTION_AMOUNT = 'Transaction amount is not positive';

    public static function withMessage(string $message): self
    {
        return new self($message);
    }
}
