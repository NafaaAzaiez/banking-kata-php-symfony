<?php

declare(strict_types=1);

namespace App\Usecase\OpenAccount;

use App\Domain\Entity\BankAccount;
use App\Domain\Exception\RequestValidationException;
use App\Domain\Generators\AccountNumberGenerator;
use App\Domain\Repository\BankAccountRepository;
use App\Domain\Validation\Validator;

class OpenAccountUseCase
{
    private AccountNumberGenerator $accountNumberGenerator;

    private BankAccountRepository $bankAccountRepository;

    public function __construct(AccountNumberGenerator $accountNumberGenerator, BankAccountRepository $bankAccountRepository)
    {
        $this->accountNumberGenerator = $accountNumberGenerator;
        $this->bankAccountRepository = $bankAccountRepository;
    }

    public function __invoke(OpenAccountRequest $request): OpenAccountResponse
    {
        Validator::assertNotBlank($request->firstName, RequestValidationException::INVALID_FIRST_NAME);
        Validator::assertNotBlank($request->lastName, RequestValidationException::INVALID_LAST_NAME);
        Validator::assertNotNegative($request->initialBalance, RequestValidationException::INITIAL_BALANCE_NEGATIVE);

        $accountNumber = $this->accountNumberGenerator->generate();
        $this->bankAccountRepository->add(new BankAccount($accountNumber));

        return new OpenAccountResponse($accountNumber);
    }
}
