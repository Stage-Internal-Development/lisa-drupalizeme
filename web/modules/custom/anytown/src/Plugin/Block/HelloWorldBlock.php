<?php

declare(strict_types=1);

namespace Drupal\anytown\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\anytown\ForecastClientInterface;

/**
 * Provides a 'Hello World' block.
 */

#[Block(
  id: 'anytown_hello_world',
  admin_label: new TranslatableMarkup('Hello World'),
  category: new TranslatableMarkup('Custom'),
)]
class HelloWorldBlock extends BlockBase implements ContainerFactoryPluginInterface {

  private $currentUser;
  private $forecastClient;


  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountProxyInterface $current_user, ForecastClientInterface $forecast_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->currentUser = $current_user;
    $this->forecastClient = $forecast_client;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('anytown.forecast_client')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $name = $this->currentUser->getDisplayName();
    $url = 'https://module-developer-guide-demo-site.ddev.site/modules/custom/anytown/data/weather_forecast.json';
    $forecast_data = $this->forecastClient->getForecastData($url);

    $rows = [];
    $highest = 0;
    $lowest = 0;

    if($forecast_data) {
      foreach ($forecast_data as $item) {
        [
          'weekday' => $weekday,
          'description' => $description,
          'high' => $high,
          'low' => $low,
          'icon' => $icon,
        ] = $item;

        $highest = max($highest, $high);
        $lowest = min($lowest, $low);
      }
    }

    if ($this->currentUser->isAuthenticated()) {
      $build['content'] = [
        '#markup' => 
        '<p>' . $this-> t('Hello, %name', ['%name' => $name]) . '</p><p>' . $this->t("The high for the weekend is @highest and the low is @lowest.",
          [
            '@highest' => $highest,
            '@lowest' => $lowest,
          ]
        ) . '</p>',
      ];
    } else {
      $build['content'] = [
        '#markup' => $this->t('Hello, Guest'),
      ];
    }

    $build['content']['#cache'] = [
      'max-age' => 0,
    ];

    return $build;
  }
}
