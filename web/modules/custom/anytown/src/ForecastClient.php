<?php

declare(strict_types=1);

namespace Drupal\anytown;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

/**
 * Forecast retrieval API client.
 */
class ForecastClient implements ForecastClientInterface {

  /**
   * Guzzle HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Logger channel.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Forecast adapter.
   *
   * @var \Drupal\anytown\ForecastAdapterInterface
   */
  protected $adapter;

  /**
   * Construct a forecast API client.
   *
   * @param \GuzzleHttp\ClientInterface $httpClient
   *    Guzzle HTTP client.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   Logger factory service.
   */
  public function __construct(
    ClientInterface $httpClient,
    LoggerChannelFactoryInterface $logger_factory,
    ForecastAdapterInterface $adapter
  ) {
    $this->httpClient = $httpClient;
    $this->logger = $logger_factory->get('anytown');
    $this->adapter = $adapter;
  }

  /**
   * {@inheritDoc}
   */
  public function getForecastData(string $url) : ?array {
    try {
      $response = $this->httpClient->get($url);
      $json = json_decode($response->getBody()->getContents());
    }
    catch (GuzzleException $e) {
      $this->logger->warning($e->getMessage());
      return NULL;
    }

    return $this->adapter->parse($json);

  }

}
