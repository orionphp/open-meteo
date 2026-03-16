<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Tests\Translation;

use Orionphp\OpenMeteo\Enum\Locale;
use Orionphp\OpenMeteo\Enum\WeatherCode;
use Orionphp\OpenMeteo\Exception\MissingWeatherCodeTranslation;
use Orionphp\OpenMeteo\Exception\OpenMeteoException;
use Orionphp\OpenMeteo\Translation\WeatherCodeTranslation;
use PHPUnit\Framework\TestCase;

final class WeatherCodeTranslationTest extends TestCase
{
    private string $tmpDir;

    protected function setUp(): void
    {
        $this->tmpDir = sys_get_temp_dir() . '/om_translation_' . uniqid('', true);
        mkdir($this->tmpDir);
    }

    protected function tearDown(): void
    {
        foreach (glob($this->tmpDir . '/*.php') ?: [] as $file) {
            unlink($file);
        }

        rmdir($this->tmpDir);
    }

    public function testTranslateReturnsValue(): void
    {
        file_put_contents(
            $this->tmpDir . '/en.php',
            '<?php return [0 => "Clear sky"];'
        );

        $translator = new WeatherCodeTranslation($this->tmpDir);

        $result = $translator->translate(WeatherCode::ClearSky, Locale::EN);

        $this->assertSame('Clear sky', $result);
    }

    public function testTranslateReturnsNullIfCodeMissing(): void
    {
        file_put_contents(
            $this->tmpDir . '/en.php',
            '<?php return [1 => "Partly cloudy"];'
        );

        $translator = new WeatherCodeTranslation($this->tmpDir);

        $result = $translator->translate(WeatherCode::ClearSky, Locale::EN);

        $this->assertNull($result);
    }

    public function testMissingLocaleFileThrowsException(): void
    {
        $translator = new WeatherCodeTranslation($this->tmpDir);

        $this->expectException(MissingWeatherCodeTranslation::class);

        $translator->translate(WeatherCode::ClearSky, Locale::EN);
    }

    public function testInvalidTranslationFileThrowsException(): void
    {
        file_put_contents(
            $this->tmpDir . '/en.php',
            '<?php return "invalid";'
        );

        $translator = new WeatherCodeTranslation($this->tmpDir);

        $this->expectException(OpenMeteoException::class);

        $translator->translate(WeatherCode::ClearSky, Locale::EN);
    }

    public function testCacheIsUsed(): void
    {
        file_put_contents(
            $this->tmpDir . '/en.php',
            '<?php return [0 => "Clear sky"];'
        );

        $translator = new WeatherCodeTranslation($this->tmpDir);

        $this->assertSame(
            'Clear sky',
            $translator->translate(WeatherCode::ClearSky, Locale::EN)
        );

        // zweite Abfrage nutzt Cache-Zweig
        $this->assertSame(
            'Clear sky',
            $translator->translate(WeatherCode::ClearSky, Locale::EN)
        );
    }
}
