<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Enum;

use Orionphp\OpenMeteo\Enum\BackedEnumValuesTrait;
use PHPUnit\Framework\TestCase;

enum StringBackedTestEnum: string
{
    use BackedEnumValuesTrait;

    case FIRST = 'first';
    case SECOND = 'second';
    case THIRD = 'third';
}

enum IntBackedTestEnum: int
{
    use BackedEnumValuesTrait;

    case ONE = 1;
    case TWO = 2;
    case THREE = 3;
}

final class BackedEnumValuesTraitTest extends TestCase
{
    public function testItReturnsAllStringBackedValues(): void
    {
        $this->assertSame(
            ['first', 'second', 'third'],
            StringBackedTestEnum::values()
        );
    }

    public function testItReturnsAllStringBackedNames(): void
    {
        $this->assertSame(
            ['FIRST', 'SECOND', 'THIRD'],
            StringBackedTestEnum::names()
        );
    }

    public function testItReturnsAllIntBackedValues(): void
    {
        $this->assertSame(
            [1, 2, 3],
            IntBackedTestEnum::values()
        );
    }

    public function testItReturnsAllIntBackedNames(): void
    {
        $this->assertSame(
            ['ONE', 'TWO', 'THREE'],
            IntBackedTestEnum::names()
        );
    }

    public function testValuesAndNamesFollowCasesOrder(): void
    {
        $cases = StringBackedTestEnum::cases();

        $valuesFromCases = array_map(
            static fn($case) => $case->value,
            $cases
        );

        $namesFromCases = array_map(
            static fn($case) => $case->name,
            $cases
        );

        $this->assertSame($valuesFromCases, StringBackedTestEnum::values());
        $this->assertSame($namesFromCases, StringBackedTestEnum::names());
    }
}