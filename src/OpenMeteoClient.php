<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

use function is_array;

use JsonException;
use Orionphp\OpenMeteo\Exception\OpenMeteoException;
use Orionphp\OpenMeteo\Factory\ForecastFactory;
use Orionphp\OpenMeteo\Http\ForecastQueryBuilder;
use Orionphp\OpenMeteo\Request\ForecastRequest;
use Orionphp\OpenMeteo\Response\Forecast;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

use function sprintf;

use Throwable;

final class OpenMeteoClient
{
    private const string BASE_API_URL = 'https://api.open-meteo.com/v1/';
    private const int DEFAULT_TIMEOUT = 10;

    private ClientInterface $client;

    public function __construct(
        private readonly LoggerInterface $logger,
        ?ClientInterface                 $client = null
    ) {
        $this->client = $client ?? new Client([
            'base_uri' => self::BASE_API_URL,
            'timeout' => self::DEFAULT_TIMEOUT,
        ]);
    }

    public function forecast(ForecastRequest $request): Forecast
    {
        $query = ForecastQueryBuilder::build($request);

        $this->logger->info('OpenMeteo forecast request', [
            'query' => $query,
        ]);

        $response = $this->sendRequest($query);

        $data = $this->decodeJson($response);
        return ForecastFactory::fromApiResponse($data, $request);
    }

    /**
     * @param array<string, string|float> $query
     */
    private function sendRequest(array $query, string $endpoint = 'forecast'): ResponseInterface
    {
        try {
            $response = $this->client->request('GET', $endpoint, [
                'query' => $query,
            ]);
        } catch (Throwable $e) {
            $this->logger->error('OpenMeteo HTTP request failed', [
                'exception' => $e,
            ]);

            throw new OpenMeteoException(
                'Open-Meteo HTTP request failed.',
                0,
                $e
            );
        }

        if ($response->getStatusCode() !== 200) {
            $this->logger->error('OpenMeteo returned non-200 status code', [
                'status' => $response->getStatusCode(),
            ]);

            throw new OpenMeteoException(
                sprintf(
                    'Open-Meteo returned status code %d.',
                    $response->getStatusCode()
                )
            );
        }

        return $response;
    }

    /**
     * @return array<string, mixed>
     */
    private function decodeJson(ResponseInterface $response): array
    {
        $body = (string)$response->getBody();

        try {
            $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            $this->logger->error('Invalid JSON response from OpenMeteo', [
                'body' => $body,
                'exception' => $e,
            ]);

            throw new OpenMeteoException(
                'Invalid JSON response from Open-Meteo.',
                0,
                $e
            );
        }

        if (!is_array($data)) {
            throw new OpenMeteoException(
                'Unexpected JSON structure from Open-Meteo.'
            );
        }

        /** @var array<string, mixed> $data */
        return $data;
    }
}
