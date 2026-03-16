<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Translation;

use function is_array;
use function is_file;

use Orionphp\OpenMeteo\Enum\Locale;

use Orionphp\OpenMeteo\Enum\WeatherCode;
use Orionphp\OpenMeteo\Exception\MissingWeatherCodeTranslation;
use Orionphp\OpenMeteo\Exception\OpenMeteoException;

use function rtrim;
use function sprintf;

final class WeatherCodeTranslation
{
    private string $path;

    /** @var array<string, array<int,string>> */
    private array $cache = [];

    public function __construct(?string $path = null)
    {
        $this->path = rtrim($path ?? __DIR__ . '/locales', '/');
    }

    public function translate(WeatherCode $code, Locale $locale): ?string
    {
        $translations = $this->load($locale);

        return $translations[$code->value] ?? null;
    }

    /**
     * @return array<int,string>
     */
    private function load(Locale $locale): array
    {
        if (isset($this->cache[$locale->value])) {
            return $this->cache[$locale->value];
        }

        $file = $this->path . '/' . $locale->value . '.php';

        if (!is_file($file)) {
            throw new MissingWeatherCodeTranslation(
                sprintf('Translation file not found for locale "%s" in path "%s".', $locale->value, $this->path)
            );
        }

        $translations = require $file;

        if (!is_array($translations)) {
            throw new OpenMeteoException(
                sprintf('Translation file "%s" must return an array.', $file)
            );
        }

        /** @var array<int,string> $translations */
        $this->cache[$locale->value] = $translations;

        return $translations;
    }
}
