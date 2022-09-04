<?php

declare(strict_types=1);

namespace Tests\Usecase;

use App\Domain\Account\AccountNumber;
use App\Domain\Account\BankAccount;
use App\Domain\Exception\RequestValidationException;
use App\Infrastructure\Fake\FakeAccountNumberGenerator;
use App\Infrastructure\Fake\FakeBankAccountRepository;
use App\Usecase\OpenAccount\OpenAccountRequest;
use App\Usecase\OpenAccount\OpenAccountResponse;
use App\Usecase\OpenAccount\OpenAccountUseCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Common\Factory;

class OpenAccountUseCaseTest extends KernelTestCase
{
    private OpenAccountUseCase $useCase;

    private FakeAccountNumberGenerator $accountNumberGenerator;

    private FakeBankAccountRepository $bankAccountRepository;

    protected function setUp(): void
    {
        $this->accountNumberGenerator = new FakeAccountNumberGenerator();
        $this->bankAccountRepository = new FakeBankAccountRepository();
        $this->useCase = new OpenAccountUseCase($this->accountNumberGenerator, $this->bankAccountRepository);
    }

    /**
     * @dataProvider provideAccountNumbers
     */
    public function testItOpensAccountGivenValidRequest(string $accountNumber): void
    {
        $initialBalance = 100;
        $request = new OpenAccountRequest('John', 'Doe', $initialBalance);
        $expectedResponse = new OpenAccountResponse($accountNumber);
        $this->registerAccountNumber($accountNumber);
        $expectedBankAccount = Factory::createBankAccount($accountNumber, $request->firstName, $request->lastName, $initialBalance);

        $response = $this->useCase->__invoke($request);

        $retrievedBankAccount = $this->find($accountNumber);

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedBankAccount, $retrievedBankAccount);
    }

    /**
     * @dataProvider provideEmptyValues
     */
    public function testItThrowsExceptionGivenEmptyFirstName(string $firstName): void
    {
        $request = new OpenAccountRequest($firstName, 'Doe', 0);

        $this->expectException(RequestValidationException::class);
        $this->expectExceptionMessage(RequestValidationException::INVALID_FIRST_NAME);
        $this->useCase->__invoke($request);
    }

    /**
     * @dataProvider provideEmptyValues
     */
    public function testItThrowsExceptionGivenEmptyLastName(string $lastName): void
    {
        $request = new OpenAccountRequest('Doe', $lastName, 0);

        $this->expectException(RequestValidationException::class);
        $this->expectExceptionMessage(RequestValidationException::INVALID_LAST_NAME);
        $this->useCase->__invoke($request);
    }

    public function testItThrowsExceptionGivenNegativeInitialBalance(): void
    {
        $request = new OpenAccountRequest('John', 'Doe', -1);

        $this->expectException(RequestValidationException::class);
        $this->expectExceptionMessage(RequestValidationException::INITIAL_BALANCE_NEGATIVE);
        $this->useCase->__invoke($request);
    }

    /**
     * @phpstan-ignore-next-line
     */
    private function provideAccountNumbers(): array
    {
        return [['A001'], ['B002'], ['C003']];
    }

    /**
     * @phpstan-ignore-next-line
     */
    private function provideEmptyValues(): array
    {
        return [[''], [' '], ['   ']];
    }

    private function registerAccountNumber(string $accountNumber): void
    {
        $this->accountNumberGenerator->add(new AccountNumber($accountNumber));
    }

    private function find(string $accountNumber): BankAccount
    {
        return $this->bankAccountRepository->find(new AccountNumber($accountNumber));
    }
}
