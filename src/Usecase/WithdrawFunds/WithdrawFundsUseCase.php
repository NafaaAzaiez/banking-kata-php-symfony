<?php

declare(strict_types=1);

namespace App\Usecase\WithdrawFunds;

use App\Domain\Account\AccountNumber;
use App\Domain\Account\BankAccountRepository;
use App\Domain\Exception\RequestValidationException;
use App\Domain\Validation\Validator;

class WithdrawFundsUseCase
{
    private BankAccountRepository $bankAccountRepository;

    public function __construct(BankAccountRepository $bankAccountRepository)
    {
        $this->bankAccountRepository = $bankAccountRepository;
    }

    public function __invoke(WithdrawFundsRequest $request): WithdrawFundsResponse
    {
        Validator::assertNotBlank($request->accountNumber, RequestValidationException::EMPTY_ACCOUNT_NUMBER);
        Validator::assertStrictPositive($request->amount, RequestValidationException::NON_POSITIVE_TRANSACTION_AMOUNT);

        $bankAccount = $this->bankAccountRepository->find(new AccountNumber($request->accountNumber));

        $bankAccount->withdraw($request->amount);

        $this->bankAccountRepository->update($bankAccount);

        return new WithdrawFundsResponse($bankAccount->getBalance());
    }
}
