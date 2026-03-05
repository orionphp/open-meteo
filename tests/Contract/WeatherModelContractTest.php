<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Contract;

use Orionphp\OpenMeteo\Enum\WeatherModel;
use PHPUnit\Framework\TestCase;

final class WeatherModelContractTest extends TestCase
{
    public function testModelValuesMatchApiSuffixes(): void
    {
        $expected = [
            'best_match',
            'ecmwf_ifs',
            'gfs',
            'icon_global',
            'gem_global',
            'jma_gsm',
            'ukmo_global',
            'icon_eu',
            'icon_d2',
            'meteofrance_arpege',
            'meteofrance_arome',
            'dmi_harmonie_arome_europe',
            'knmi_harmonie_arome_netherlands',
            'hrrr',
            'nam_conus',
        ];

        $actual = array_map(
            static fn ($m) => $m->value,
            WeatherModel::cases()
        );

        $this->assertSame($expected, $actual);
    }
}