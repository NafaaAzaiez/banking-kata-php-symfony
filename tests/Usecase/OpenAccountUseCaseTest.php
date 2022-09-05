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
use Tests\Builder\Entity\BankAccountBuilder;

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
        $firstName = 'John';
        $lastName = 'Doe';

        $this->registerAccountNumber($accountNumber);

        $response = $this->openAccount($firstName, $lastName, $initialBalance);

        $this->assertExpectedResponse($response, $accountNumber);
        $this->assertContainsBankAccount($accountNumber, $firstName, $lastName, $initialBalance);
    }

    /**
     * @dataProvider provideEmptyValues
     */
    public function testItThrowsExceptionGivenEmptyFirstName(string $firstName): void
    {
        $this->expectExceptionWithMessage(RequestValidationException::class, RequestValidationException::INVALID_FIRST_NAME);

        $this->openAccount($firstName, 'Doe', 100);
    }

    /**
     * @dataProvider provideEmptyValues
     */
    public function testItThrowsExceptionGivenEmptyLastName(string $lastName): void
    {
        $this->expectExceptionWithMessage(RequestValidationException::class, RequestValidationException::INVALID_LAST_NAME);

        $this->openAccount('Doe', $lastName, 100);
    }

    public function testItThrowsExceptionGivenNegativeInitialBalance(): void
    {
        $this->expectExceptionWithMessage(RequestValidationException::class, RequestValidationException::INITIAL_BALANCE_NEGATIVE);

        $this->openAccount('John', 'Doe', -1);
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

    private function assertExpectedResponse(OpenAccountResponse $response, string $expectedAccountNumber): void
    {
        $expectedResponse = new OpenAccountResponse($expectedAccountNumber);
        $this->assertEquals($expectedResponse, $response);
    }

    private function assertContainsBankAccount(string $accountNumber, string $firstName, string $lastName, int $expectedBalance): void
    {
        $expectedBankAccount = BankAccountBuilder::create()
            ->withAccountNumber($accountNumber)
            ->withFirstName($firstName)
            ->withLastName($lastName)
            ->withBalance($expectedBalance)
            ->build()
        ;
        $retrievedBankAccount = $this->find($accountNumber);

        $this->assertEquals($expectedBankAccount, $retrievedBankAccount);
    }

    private function openAccount(string $firstName, string $lastName, int $initialBalance): OpenAccountResponse
    {
        $request = new OpenAccountRequest($firstName, $lastName, $initialBalance);

        return $this->useCase->__invoke($request);
    }

    private function expectExceptionWithMessage(string $exceptionClass, string $exceptionMessage): void
    {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);
    }
}
