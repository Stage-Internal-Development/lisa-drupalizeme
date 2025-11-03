<?php

declare(strict_types=1);

namespace Drupal\anytown\Adapter;

use Drupal\anytown\ForecastAdapterInterface;

/**
 * Adapter for OpenWeatherMap API responses.
 */
class OpenWeatherMapAdapter implements ForecastAdapterInterface {
  public function parse(object $response) : array {

    $forecast = [];
    foreach ($response->list as $day) {
      $forecast[$day->day] = [
        'weekday' => ucfirst($day->day),
        'description' => $day->weather[0]->description,
        'high' => $this->kelvinToFahrenheit($day->main->temp_max),
        'low' => $this->kelvinToFahrenheit($day->main->temp_min),
        'icon' => $day->weather[0]->icon,
      ];
    }

    return $forecast;
  }

  /**
   * Helper to convert temperature values form Kelvin to Fahrenheit.
   *
   * @param float $kelvin
   *   Temperature in Kelvin.
   *
   * @return float
   *   Temperature in Fahrenheit.
   */
  protected function kelvinToFahrenheit(float $kelvin) : float {
    return round(($kelvin - 273.15) * 9 / 5 + 32);
  }
}
