<?php

declare(strict_types=1);

namespace Drupal\anytown\Controller;

use Drupal\anytown\ForecastClientInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controllers for anytown.weather_page route.
 */
class WeatherPage extends ControllerBase {

  /**
   * Forecast API client.
   *
   * @var \Drupal\anytown\ForecastClientInterface
   */
  private $forecastClient;

  /**
   * WeatherPage controller constructor.
   *
   * @param \Drupal\anytown\ForecastClientInterface $forecast_client
   *   Forecast API client service.
   */
  public function __construct(ForecastClientInterface $forecast_client) {
    $this->forecastClient = $forecast_client;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('anytown.forecast_client')
    );
  }
  /**
   * Builds the response.
   */
  public function build(string $style): array {
    // Style should be one of 'short' or 'extended'. Ande default to 'short'.
    $style = (in_array($style, ['short', 'extended'])) ? $style : 'short';

    // The currentUser method is inherited from ControllerBase.
    // It returns an instance of \Drupal\Core\Session\AccountInterface.
    // The getDisplayName method comes from the user object that the currentUser() returns.
    $display_name = $this->currentUser()->getDisplayName();

    $url = 'https://module-developer-guide-demo-site.ddev.site/modules/custom/anytown/data/weather_forecast.json';
    $forecast_data = $this->forecastClient->getForecastData($url);

    if($forecast_data) {
      $forecast = '<ul>';
      foreach ($forecast_data as $item) {
        [
        'weekday' => $weekday,
        'description' => $description,
        'high' => $high,
        'low' => $low,
        ] = $item;
        $forecast .="<li>$weekday will be <em>$description</em> with a high of $high and a low of $low.</li>";
      }
      $forecast .= '</ul>';
    }
    else {
      $forecast = '<p>Sorry $display_name, no weather data available.</p>';
    }

    $output = "<p>Hi $display_name, check out this weekend's weather forecast:</p>";
    $output .= $forecast;

    return [
      '#markup' => $output,
    ];
  }
}
