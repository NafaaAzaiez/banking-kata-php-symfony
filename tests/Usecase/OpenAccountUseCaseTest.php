<?php

declare(strict_types=1);

namespace Tests\Usecase;

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
        $request = new OpenAccountRequest();
        $expectedResponse = new OpenAccountResponse();

        $response = $this->useCase->__invoke($request);

        $this->assertEquals($expectedResponse, $response);
    }
}
