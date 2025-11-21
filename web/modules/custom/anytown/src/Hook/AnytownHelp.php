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
      $name = $this->currentUser->getDisplayName();
      return '<p>' . t('Hello @name, Help for the Anytown module.', ['@name' => $name]) . '</p>';
    }
  }

}
