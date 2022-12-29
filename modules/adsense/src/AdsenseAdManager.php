<?php

namespace Drupal\adsense;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Adsense plugin manager.
 */
class AdsenseAdManager extends DefaultPluginManager {

  /**
   * Constructs an AdsenseAdManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/AdsenseAd', $namespaces, $module_handler, 'Drupal\adsense\AdsenseAdInterface', 'Drupal\adsense\Annotation\AdsenseAd');

    $this->alterInfo('adsense_ad_info');
    $this->setCacheBackend($cache_backend, 'adsense_ad');
  }

}
