<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Fake;

use App\Domain\Account\AccountNumber;
use App\Infrastructure\Fake\FakeAccountNumberGenerator;
use PHPUnit\Framework\TestCase;

class FakeAccountNumberGeneratorTest extends TestCase
{
    private FakeAccountNumberGenerator $accountNumberGenerator;

    protected function setUp(): void
    {
        $this->accountNumberGenerator = new FakeAccountNumberGenerator();
    }

    public function testItThrowsExceptionWhenNoElement(): void
    {
        $this->assertGenerateThrowsException();
    }

    public function testItGenerateNumberWhenThereIsOneAvailable(): void
    {
        $expectedValue = 'A45678';
        $this->registerAccountNumber($expectedValue);
        $this->assertGeneratedNumberEquals($expectedValue);
    }

    public function testItGenerateMultipleNumbersWhenPossibleThenThrowsException(): void
    {
        $expectedValue1 = 'A45678';
        $expectedValue2 = 'B56787';
        $expectedValue3 = 'C10986';
        $this->registerAccountNumber($expectedValue1);
        $this->registerAccountNumber($expectedValue2);
        $this->registerAccountNumber($expectedValue3);

        $this->assertGeneratedNumberEquals($expectedValue1);
        $this->assertGeneratedNumberEquals($expectedValue2);
        $this->assertGeneratedNumberEquals($expectedValue3);
        $this->assertGenerateThrowsException();
    }

    private function assertGeneratedNumberEquals(string $number): void
    {
        $this->assertEquals($number, $this->accountNumberGenerator->generate()->value());
    }

    private function assertGenerateThrowsException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(FakeAccountNumberGenerator::COULD_NOT_GENERATE_NUMBER_MESSAGE);

        $this->accountNumberGenerator->generate();
    }

    private function registerAccountNumber(string $accountNumber): void
    {
        $this->accountNumberGenerator->add(new AccountNumber($accountNumber));
    }
}
