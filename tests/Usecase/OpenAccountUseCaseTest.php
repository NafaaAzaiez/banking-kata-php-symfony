<?php

declare(strict_types=1);

namespace Tests\Usecase;

use App\Domain\Exception\RequestValidationException;
use App\Usecase\OpenAccount\OpenAccountRequest;
use App\Usecase\OpenAccount\OpenAccountResponse;
use App\Usecase\OpenAccount\OpenAccountUseCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Fake\FakeAccountNumberGenerator;

class OpenAccountUseCaseTest extends KernelTestCase
{
    private OpenAccountUseCase $useCase;

    private FakeAccountNumberGenerator $accountNumberGenerator;

    protected function setUp(): void
    {
        $this->accountNumberGenerator = new FakeAccountNumberGenerator();
        $this->useCase = new OpenAccountUseCase($this->accountNumberGenerator);
    }

    public function testItOpensAccountWhenRequestIsValid()
    {
        $request = new OpenAccountRequest('John', 'Doe', 0);
        $this->accountNumberGenerator->add('fakeAccountNumber');
        $expectedResponse = new OpenAccountResponse('fakeAccountNumber');

        $response = $this->useCase->__invoke($request);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @dataProvider provideAccountNumbers
     */
    public function testItOpensAccountWithGeneratedAccountNumbers(string $accountNumber)
    {
        $request = new OpenAccountRequest('John', 'Doe', 0);
        $expectedResponse = new OpenAccountResponse($accountNumber);
        $this->accountNumberGenerator->add($accountNumber);

        $response = $this->useCase->__invoke($request);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @dataProvider emptyValues
     */
    public function testItThrowsExceptionWhenFirstNameIsEmpty(string $firstName)
    {
        $request = new OpenAccountRequest($firstName, 'Doe', 0);

        $this->expectException(RequestValidationException::class);
        $this->expectExceptionMessage(RequestValidationException::INVALID_FIRST_NAME);
        $this->useCase->__invoke($request);
    }

    /**
     * @dataProvider emptyValues
     */
    public function testItThrowsExceptionWhenLastNameIsEmpty(string $lastName)
    {
        $request = new OpenAccountRequest('Doe', $lastName, 0);

        $this->expectException(RequestValidationException::class);
        $this->expectExceptionMessage(RequestValidationException::INVALID_LAST_NAME);
        $this->useCase->__invoke($request);
    }

    public function testItThrowsExceptionWhenInitialBalanceIsNegative()
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

    private function emptyValues(): array
    {
        return [
            [''],
            [' '],
            ['   '],
            ]
        ;
    }
}
