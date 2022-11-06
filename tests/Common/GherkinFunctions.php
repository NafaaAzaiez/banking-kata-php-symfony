<?php

declare(strict_types=1);

namespace Tests\Common;

use App\Infrastructure\Fake\FakeAccountNumberGenerator;
use Tests\Common\Given\AccountNumberGeneratorGiven;

trait GherkinFunctions
{
    public function givenThatGenerator(FakeAccountNumberGenerator $generator): AccountNumberGeneratorGiven
    {
        return AccountNumberGeneratorGiven::create($generator);
    }
}
