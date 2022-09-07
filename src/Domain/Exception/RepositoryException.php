<?php

declare(strict_types=1);

namespace App\Domain\Exception;

class RepositoryException extends \DomainException
{
    public const BANK_ACCOUNT_NOT_FOUND = 'Bank account not found';

    public const CAN_NOT_UPDATE_NON_EXISTENT_BANK_ACCOUNT = 'Can not update non-existent bank account';

    public const BANK_ACCOUNT_ALREADY_EXISTS = 'Can not add bank account as it already exists';

    public static function withMessage(string $message): self
    {
        return new self($message);
    }
}
