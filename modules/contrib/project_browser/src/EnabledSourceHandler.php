<?php

namespace Drupal\project_browser;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\project_browser\Plugin\ProjectBrowserSourceManager;
use Psr\Log\LoggerInterface;

/**
 * Defines enabled source.
 */
class EnabledSourceHandler {

  public function __construct(
    private readonly LoggerInterface $logger,
    private readonly ConfigFactoryInterface $configFactory,
    private readonly ProjectBrowserSourceManager $pluginManager,
  ) {}

  /**
   * Returns all plugin instances corresponding to the enabled_source config.
   *
   * @return \Drupal\project_browser\Plugin\ProjectBrowserSourceInterface[]
   *   Array of plugin instances.
   */
  public function getCurrentSources(): array {
    $plugin_instances = [];
    $config = $this->configFactory->get('project_browser.admin_settings');

    $plugin_ids = $config->get('enabled_sources');
    foreach ($plugin_ids as $plugin_id) {
      if (!$this->pluginManager->hasDefinition($plugin_id)) {
        // Ignore if the plugin does not exist, but log it.
        $this->logger->warning('Project browser tried to load the enabled source %source, but the plugin does not exist. Make sure you have run update.php after updating the Project Browser module.', ['%source' => $plugin_id]);
      }
      else {
        $plugin_instances[$plugin_id] = $this->pluginManager->createInstance($plugin_id);
      }
    }

    return $plugin_instances;
  }

}
