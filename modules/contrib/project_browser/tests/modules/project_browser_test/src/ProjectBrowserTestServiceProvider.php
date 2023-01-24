<?php

namespace Drupal\project_browser_test;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Overrides the module installer service.
 */
class ProjectBrowserTestServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    $definition = $container->getDefinition('module_installer');
    $definition->setClass('Drupal\project_browser_test\Extension\TestModuleInstaller')
      ->setLazy(FALSE);
  }

}
