<?php

namespace Drupal\project_browser;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\project_browser\ComposerInstaller\Installer;
use PhpTuf\ComposerStager\Infrastructure\Factory\Path\PathFactoryInterface;
use Symfony\Component\DependencyInjection\Reference;

class ProjectBrowserServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    if (array_key_exists('package_manager', $container->getParameter('container.modules'))) {
      parent::register($container);
      $container->register('project_browser.installer')
        ->setClass(Installer::class)
        ->setArguments([
          new Reference('config.factory'),
          new Reference('package_manager.path_locator'),
          new Reference('package_manager.beginner'),
          new Reference('package_manager.stager'),
          new Reference('package_manager.committer'),
          new Reference('file_system'),
          new Reference('event_dispatcher'),
          new Reference('tempstore.shared'),
          new Reference('datetime.time'),
          new Reference(PathFactoryInterface::class),
          new Reference('package_manager.failure_marker'),
        ]);
    }
  }

}
