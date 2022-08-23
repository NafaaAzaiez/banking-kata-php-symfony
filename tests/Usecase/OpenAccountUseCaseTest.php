<?php

declare(strict_types=1);

namespace Tests\Usecase;

use App\Domain\Entity\BankAccount;
use App\Domain\Exception\RequestValidationException;
use App\Usecase\OpenAccount\OpenAccountRequest;
use App\Usecase\OpenAccount\OpenAccountResponse;
use App\Usecase\OpenAccount\OpenAccountUseCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Fake\FakeAccountNumberGenerator;
use Tests\Fake\FakeBankAccountRepository;

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
    public function testItOpensAccountGivenValidRequest(string $accountNumber)
    {
        $request = new OpenAccountRequest('John', 'Doe', 0);
        $expectedResponse = new OpenAccountResponse($accountNumber);
        $this->accountNumberGenerator->add($accountNumber);
        $expectedBankAccount = new BankAccount($accountNumber);

        $response = $this->useCase->__invoke($request);

        $retrievedBankAccount = $this->bankAccountRepository->find($accountNumber);

        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($expectedBankAccount, $retrievedBankAccount);
    }

    /**
     * @dataProvider provideEmptyValues
     */
    public function testItThrowsExceptionGivenEmptyFirstName(string $firstName)
    {
        $request = new OpenAccountRequest($firstName, 'Doe', 0);

        $this->expectException(RequestValidationException::class);
        $this->expectExceptionMessage(RequestValidationException::INVALID_FIRST_NAME);
        $this->useCase->__invoke($request);
    }

    /**
     * @dataProvider provideEmptyValues
     */
    public function testItThrowsExceptionGivenEmptyLastName(string $lastName)
    {
        $request = new OpenAccountRequest('Doe', $lastName, 0);

        $this->expectException(RequestValidationException::class);
        $this->expectExceptionMessage(RequestValidationException::INVALID_LAST_NAME);
        $this->useCase->__invoke($request);
    }

    public function testItThrowsExceptionGivenNegativeInitialBalance()
    {
        $request = new OpenAccountRequest('John', 'Doe', -1);

        $this->expectException(RequestValidationException::class);
        $this->expectExceptionMessage(RequestValidationException::INITIAL_BALANCE_NEGATIVE);
        $this->useCase->__invoke($request);
    }

    public function provideAccountNumbers(): array
    {
        return [['A001'], ['B002'], ['C003']];
    }

    private function provideEmptyValues(): array
    {
        return [[''], [' '], ['   ']];
    }
}
