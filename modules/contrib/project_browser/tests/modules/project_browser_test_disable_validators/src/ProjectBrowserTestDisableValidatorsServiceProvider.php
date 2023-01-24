<?php

namespace Drupal\project_browser_test_disable_validators;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\Core\Site\Settings;

/**
 * Disables specific readiness validators in the service container.
 */
class ProjectBrowserTestDisableValidatorsServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container): void {
    parent::alter($container);

    $validators = Settings::get('project_browser_test_disable_validators', []);
    array_walk($validators, [$container, 'removeDefinition']);
  }

}
