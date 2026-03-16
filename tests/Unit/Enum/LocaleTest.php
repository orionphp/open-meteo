<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Unit\Enum;

use Orionphp\OpenMeteo\Enum\Locale;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ValueError;

final class LocaleTest extends TestCase
{
    public function testItHasExpectedNumberOfCases(): void
    {
        $this->assertCount(4, Locale::cases());
    }

    public function testValuesReturnsAllBackedValuesInOrder(): void
    {
        $this->assertSame(
            ['de', 'en', 'es', 'fr'],
            Locale::values()
        );
    }

    public function testNamesReturnsAllCaseNamesInOrder(): void
    {
        $this->assertSame(
            ['DE', 'EN', 'ES', 'FR'],
            Locale::names()
        );
    }

    public function testFromMapsCorrectly(): void
    {
        $this->assertSame(Locale::DE, Locale::from('de'));
        $this->assertSame(Locale::EN, Locale::from('en'));
        $this->assertSame(Locale::ES, Locale::from('es'));
        $this->assertSame(Locale::FR, Locale::from('fr'));
    }

    public function testTryFromReturnsNullForInvalidValue(): void
    {
        $this->assertSame(null, Locale::tryFrom('xx'));
    }

    public function testFromThrowsExceptionForInvalidValue(): void
    {
        $this->expectException(ValueError::class);

        Locale::from('xx');
    }

    #[DataProvider('fromStringProvider')]
    public function testFromStringNormalizesAndFallsBack(
        string $input,
        Locale $expected
    ): void {
        $this->assertSame($expected, Locale::fromString($input));
    }

    /**
     * @return array<int|string, array{string, Locale}>
     */
    public static function fromStringProvider(): array
    {
        return [
            'exact de'        => ['de', Locale::DE],
            'exact en'        => ['en', Locale::EN],
            'uppercase DE'    => ['DE', Locale::DE],
            'mixed En'        => ['En', Locale::EN],
            'long de_DE'      => ['de_DE', Locale::DE],
            'long en-GB'      => ['en-GB', Locale::EN],
            'long es_ES'      => ['es_ES', Locale::ES],
            'unknown locale'  => ['it_IT', Locale::EN],
            'empty string'    => ['', Locale::EN],
            'one char'        => ['d', Locale::EN],
            'random string'   => ['foobar', Locale::EN],
        ];
    }

    public function testValuesContainNoDuplicates(): void
    {
        $values = Locale::values();

        $this->assertSame($values, array_values(array_unique($values)));
    }

    public function testNamesContainNoDuplicates(): void
    {
        $names = Locale::names();

        $this->assertSame($names, array_values(array_unique($names)));
    }

    public function testValuesMatchCasesImplementation(): void
    {
        $cases = Locale::cases();

        $valuesFromCases = array_map(
            static fn (Locale $case) => $case->value,
            $cases
        );

        $namesFromCases = array_map(
            static fn (Locale $case) => $case->name,
            $cases
        );

        $this->assertSame($valuesFromCases, Locale::values());
        $this->assertSame($namesFromCases, Locale::names());
    }
}
