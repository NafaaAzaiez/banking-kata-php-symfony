<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Fake;

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
        $this->accountNumberGenerator->add($expectedValue);
        $this->assertGeneratedNumberEquals($expectedValue);
    }

    public function testItGenerateMultipleNumbersWhenPossibleThenThrowsException(): void
    {
        $expectedValue1 = 'A45678';
        $expectedValue2 = 'B56787';
        $expectedValue3 = 'C10986';
        $this->accountNumberGenerator->add($expectedValue1);
        $this->accountNumberGenerator->add($expectedValue2);
        $this->accountNumberGenerator->add($expectedValue3);

        $this->assertGeneratedNumberEquals($expectedValue1);
        $this->assertGeneratedNumberEquals($expectedValue2);
        $this->assertGeneratedNumberEquals($expectedValue3);
        $this->assertGenerateThrowsException();
    }

    private function assertGeneratedNumberEquals(string $number): void
    {
        $this->assertEquals($number, $this->accountNumberGenerator->generate());
    }

    private function assertGenerateThrowsException(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(FakeAccountNumberGenerator::COULD_NOT_GENERATE_NUMBER_MESSAGE);

        $this->accountNumberGenerator->generate();
    }
}
