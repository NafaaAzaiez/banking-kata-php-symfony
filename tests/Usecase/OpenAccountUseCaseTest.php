<?php

declare(strict_types=1);

namespace Tests\Usecase;

use App\Domain\Exception\RequestValidationException;
use App\Usecase\OpenAccount\OpenAccountRequest;
use App\Usecase\OpenAccount\OpenAccountResponse;
use App\Usecase\OpenAccount\OpenAccountUseCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OpenAccountUseCaseTest extends KernelTestCase
{
    private OpenAccountUseCase $useCase;

    protected function setUp(): void
    {
        $this->useCase = new OpenAccountUseCase();
    }

    public function testResponseIsReceived()
    {
        $request = new OpenAccountRequest('John', 'Doe', 0);
        $expectedResponse = new OpenAccountResponse();

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
