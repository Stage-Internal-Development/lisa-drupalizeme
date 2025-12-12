<?php

declare(strict_types=1);

namespace Drupal\anytown\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountProxyInterface;

class AnytownHelp {

  private $currentUser;
  public function __construct(AccountProxyInterface $current_user) {
    $this->currentUser = $current_user;
  }

  /**
   * Implements hook_help().
   */
  #[Hook('help')]
  public function help($route_name, RouteMatchInterface $route_match) {
    if ($route_name === 'help.page.anytown') {
      return '<p>' . t('Help for the Anytown module.') . '</p>';
    }

    if ($route_name === 'anytown.weather_page') {
      $name = $this->currentUser->getDisplayName();
      return '<p>' . t('Hello @name! Click the button to show the extended forecast.', ['@name' => $name]) . '</p>';
    }

  }

}
