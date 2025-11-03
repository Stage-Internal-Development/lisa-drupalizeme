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

    $rows = [];

    if($forecast_data) {
      foreach ($forecast_data as $item) {
        [
          'weekday' => $weekday,
          'description' => $description,
          'high' => $high,
          'low' => $low,
          'icon' => $icon,
        ] = $item;

        $rows[] = [
          $weekday,
          [
            'data' => [
              '#markup' => '<img src="' . $icon . '" alt="' . $description . '" width="200" height="200" />',
              '#theme' => 'image',
              '#uri' => $icon,
              '#alt' => $description,
              '#width' => 200,
              '#height' => 200,
            ]
          ],
          [
            'data' => [
              '#markup' => "<em>{$description}</em> with a high of {$high} and a low of {$low}.</em>"
            ]
          ],
        ];
      }

      $weather_forecast = [
        '#type' => 'table',
        '#header' => ['Day', '', 'Forecast'],
        '#rows' => $rows,
        '#attributes' => [
          'class' => ['weather_page--forecast-table']
        ],
      ];
    }
    else {
      $weather_forecast = [
        '#markup' => '<p>Sorry $display_name, no weather data available.</p>'
      ];
    }

    $build = [
      'weather_intro' => [
        '#markup' => "<p>Hi $display_name, check out this weekend's weather forecast:</p>"
      ],
      'weather_forecast' => $weather_forecast,
      'weather_closures' => [
        '#theme' => 'item_list',
        '#title' => 'Weather-related closures',
        '#items' => [
          'Ice rink closed until winter. Please stay off while we prepare it.',
          'Parking behind Apple Lane is still closed from all the rain last weekend.'
        ],
      ],
    ];

    return $build;
  }
}
