<?php

declare(strict_types=1);

namespace Orionphp\OpenMeteo;

use function http_build_query;
use function is_array;

use JsonException;
use Orionphp\OpenMeteo\Exception\OpenMeteoException;
use Orionphp\OpenMeteo\Factory\ForecastFactory;
use Orionphp\OpenMeteo\Http\ForecastQueryBuilder;
use Orionphp\OpenMeteo\Request\ForecastRequest;
use Orionphp\OpenMeteo\Response\Forecast;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

use function sprintf;

final class OpenMeteoClient
{
    private const string BASE_API_URL = 'https://api.open-meteo.com/v1/';

    private readonly ClientInterface $httpClient;
    private readonly RequestFactoryInterface $requestFactory;
    private readonly LoggerInterface $logger;

    /**
     * @param ClientInterface|null $httpClient
     * @param RequestFactoryInterface|null $requestFactory
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        ?ClientInterface         $httpClient = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?LoggerInterface         $logger = null
    ) {

        $this->httpClient = $httpClient
            ?? throw new OpenMeteoException(
                'No PSR-18 HTTP client provided. Install one (e.g. guzzlehttp/guzzle).'
            );

        $this->requestFactory = $requestFactory
            ?? throw new OpenMeteoException(
                'No PSR-17 request factory provided. Install one (e.g. nyholm/psr7).'
            );

        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @param ForecastRequest $request
     * @return Forecast
     */
    public function forecast(ForecastRequest $request): Forecast
    {
        $query = ForecastQueryBuilder::build($request);

        $response = $this->sendRequest('forecast', $query);

        $data = $this->decodeJson($response);

        return ForecastFactory::fromApiResponse($data, $request);
    }

    /**
     * @param string $endpoint
     * @param array<string, scalar|string[]> $query
     * @return ResponseInterface
     */
    private function sendRequest(string $endpoint, array $query): ResponseInterface
    {
        $url = $this->buildUrl($endpoint, $query);

        $this->logger->info('OpenMeteo request', [
            'url' => $url,
        ]);

        $request = $this->requestFactory->createRequest('GET', $url);

        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            $this->logger->error('OpenMeteo HTTP client error', [
                'exception' => $e,
                'url' => $url,
            ]);

            throw new OpenMeteoException(
                sprintf('Open-Meteo request failed for "%s".', $url),
                0,
                $e
            );
        }

        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            $this->logger->error('OpenMeteo returned invalid status code', [
                'status' => $response->getStatusCode(),
                'url' => $url,
            ]);

            throw new OpenMeteoException(
                sprintf(
                    'Open-Meteo returned status code %d for "%s".',
                    $response->getStatusCode(),
                    $url
                )
            );
        }

        return $response;
    }

    /**
     * @param string $endpoint
     * @param array<string, scalar|string[]> $query
     * @return string
     */
    private function buildUrl(string $endpoint, array $query): string
    {
        return self::BASE_API_URL
            . $endpoint
            . '?'
            . http_build_query(
                $query,
                '',
                '&',
                PHP_QUERY_RFC3986
            );
    }

    /**
     * @param ResponseInterface $response
     * @return array<string, mixed>
     */
    private function decodeJson(ResponseInterface $response): array
    {
        $body = $response->getBody()->getContents();

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

    /**
     * @param ClientInterface $httpClient
     * @param RequestFactoryInterface $requestFactory
     * @param LoggerInterface|null $logger
     * @return self
     */
    public static function create(
        ClientInterface         $httpClient,
        RequestFactoryInterface $requestFactory,
        ?LoggerInterface        $logger = null
    ): self {
        return new self($httpClient, $requestFactory, $logger);
    }
}
