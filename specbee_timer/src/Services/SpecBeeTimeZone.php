<?php

namespace Drupal\specbee_timer\Services;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * SpecBee TimeZone Class.
 */
class SpecBeeTimeZone {

  /**
   * Config Object.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new SpecBeeTimeZone object.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * Function to get current date time from selected timezone.
   */
  public function getSpecBeeTime() {
    $timezone_data = $this->getSpecBeeConfig();

    $current_time = new DrupalDateTime('now', $timezone_data['specbee_timezone']);
    $timezone_data['current_datetime'] = $current_time->format('jS M Y - H:i A');

    return $timezone_data;
  }

  /**
   * Function to return configuration data.
   */
  public function getSpecBeeConfig() {
    $timezone_data = [];

    /** @var Drupal\Core\Config\ConfigFactoryInterface $config_factory */
    $config_data = $this->configFactory->get('specbee_timer.settings');

    $timezone_data['specbee_country'] = $config_data->get('specbee_country');
    $timezone_data['specbee_city'] = $config_data->get('specbee_city');
    $timezone_data['specbee_timezone'] = $config_data->get('specbee_timezone');

    return $timezone_data;
  }

}
