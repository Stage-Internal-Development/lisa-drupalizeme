<?php

declare(strict_types=1);

namespace Drupal\anytown\Hook;

use Drupal\Core\Hook\Attribute\Hook;

class AnytownTheme {

  /**
   *Implements hook_theme().
   */
  #[Hook('theme')]
  public function theme() : array {
    // #theme => 'weather_page'
    // #weather_info = []
    return [
      'weather_page' => [
        'variables' => [
          'weather_info' => '',
          'weather_forecast' => '',
          'short_forecast' => '',
          'weather_closures' => ''
        ],
      ],
    ];
  }
}
