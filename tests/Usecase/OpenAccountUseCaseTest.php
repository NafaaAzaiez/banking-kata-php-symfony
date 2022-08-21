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
        $request = new OpenAccountRequest('John');
        $expectedResponse = new OpenAccountResponse();

        $response = $this->useCase->__invoke($request);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @dataProvider emptyFirstNameValues
     */
    public function testItThrowsExceptionWhenFirstNameIsEmpty($firstName)
    {
        $request = new OpenAccountRequest($firstName);

        $this->expectException(RequestValidationException::class);
        $this->expectExceptionMessage(RequestValidationException::withEmptyFirstName()->getMessage());
        $this->useCase->__invoke($request);
    }

    private function emptyFirstNameValues(): array
    {
        return [
            [''],
            [' '],
            ['   '],
            ]
        ;
    }
}
