<?php

namespace Drupal\specbee_timer\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\specbee_timer\Services\SpecBeeTimeZone;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Component\Datetime\TimeInterface;

/**
 * Provides a block to display time of selected timezone.
 *
 * @Block(
 *   id = "block_specbee_timer",
 *   admin_label = @Translation("SpecBee Timezone Block"),
 * )
 */
class TimezoneBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\specbee_timer\Services\SpecBeeTimeZone definition.
   *
   * @var Drupal\specbee_timer\Services\SpecBeeTimeZone
   */
  protected $specbeeTimezone;

  /**
   * The cache.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cache;

  /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * Construct function.
   */
  public function __construct(array $configuration,
    $plugin_id,
    $plugin_definition,
    SpecBeeTimeZone $specBeeTimeZone,
    CacheBackendInterface $cache,
    TimeInterface $time
    ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->specBeeTimeZone = $specBeeTimeZone;
    $this->cache = $cache;
    $this->time = $time;
  }

  /**
   * Create function.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('specbee_timer.timezone'),
      $container->get('cache.default'),
      $container->get('datetime.time')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $bid = 'specbee_block_cache';

    if ($cache = $this->cache->get($bid)) {
      $data = $cache->data;
    }
    else {
      $data = $this->specBeeTimeZone->getSpecBeeTime();
      $this->cache->set('specbee_block_cache', $data, $this->time->getRequestTime() + (60));
    }

    return [
      '#theme' => 'specbee_timer_block',
      '#country' => $data['specbee_country'],
      '#city' => $data['specbee_city'],
      '#datetime' => $data['current_datetime'],
      '#cache' => [
        'cache-max-age' => 0,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
