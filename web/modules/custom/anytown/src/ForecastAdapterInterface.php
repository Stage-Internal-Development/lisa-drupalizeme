<?php

declare(strict_types=1);

namespace Drupal\anytown;

/**
 * Interface for forecast API response adapters.
 */

interface ForecastAdapterInterface {

  /**
   * Parse API response into standardized format.
   *
   * @param object $response
   * The decoded JSON response from the API.
   *
   * @return array
   * Standardized forecast data array.
   */

  public function parse(object $response): array;
}
