<?php

declare(strict_types=1);

namespace Drupal\anytown\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Anytown settings for this site.
 */
final class SettingsForm extends ConfigFormBase {

  /**
   * Name for module's configuration object.
   */
  const SETTINGS = 'anytown.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return self::SETTINGS;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [self::SETTINGS];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['display_forecast'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Display weather forecast'),
      '#default_value' => $this->config(self::SETTINGS)->get('display_forecast'),
    ];

    $form['location'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ZIP code of market'),
      '#description' => $this->t('Used to determine weekend weather forecast.'),
      '#default_value' => $this->config(self::SETTINGS)->get('location'),
      '#placeholder' => '90210',
    ];

    $form['weather_closures'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Weather-related closures'),
      '#description' => $this->t('List one closure per line.'),
      '#default_value' => $this->config(self::SETTINGS)->get('weather_closures'),
    ];
    return parent::buildForm($form, $form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    $location = $form_state->getValue('location');
    $value = filter_var($location, FILTER_SANITIZE_URL);
    if (!$value || strlen((string) $value) !==5) {
      $form_state->setErrorByName('location', $this->t('Location must be a valid URL.'));
    }
//    $weather_closures = $form_state->getValue('weather_closures');
//    if (!$weather_closures) {
//      $form_state->setErrorByName('weather_closures', $this->t('Are you sure there are no weather closures?'));
//    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $displayForecast = $form_state->getValue('display_forecast');
    $location = $form_state->getValue('location');
    $weatherClosures = $form_state->getValue('weather_closures');

    $this->config(self::SETTINGS)
      ->set('display_forecast', $displayForecast)
      ->set('location', $location)
      ->set('weather_closures', $weatherClosures)
      ->save();

    $this->messenger()->addMessage($this->t('Settings saved.'));
  }

}
