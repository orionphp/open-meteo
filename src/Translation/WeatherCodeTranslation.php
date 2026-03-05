<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo\Translation;

use function is_array;
use function is_file;

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

    public function translate(WeatherCode $code, string $locale): ?string
    {
        $translations = $this->load($locale);

        return $translations[$code->value] ?? null;
    }

    /**
     * @param string $locale
     * @return array<int,string>
     */
    private function load(string $locale): array
    {
        if (isset($this->cache[$locale])) {
            return $this->cache[$locale];
        }

        $file = $this->path . '/' . $locale . '.php';

        if (!is_file($file)) {
            throw new MissingWeatherCodeTranslation(
                sprintf('Translation file not found for locale "%s" in path "%s".', $locale, $this->path)
            );
        }

        $translations = require $file;

        if (!is_array($translations)) {
            throw new OpenMeteoException(
                sprintf('Translation file "%s" must return an array.', $file)
            );
        }

        /** @var array<int,string> $translations */
        $this->cache[$locale] = $translations;

        return $translations;
    }
}
