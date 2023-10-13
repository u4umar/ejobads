<?php

namespace Drupal\project_browser;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Extension\ThemeExtensionList;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\PrivateKey;
use Drupal\Core\Queue\QueueFactory;
use Drupal\Core\State\StateInterface;
use Drupal\Core\StreamWrapper\StreamWrapperManagerInterface;
use Drupal\package_manager\ComposerInspector;
use Drupal\package_manager\PathLocator;
use Drupal\project_browser\ComposerInstaller\Installer;
use Drupal\project_browser\ComposerInstaller\Validator\CoreNotUpdatedValidator;
use Drupal\project_browser\ComposerInstaller\Validator\PackageNotInstalledValidator;
use PhpTuf\ComposerStager\API\Path\Factory\PathFactoryInterface;
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
      $container->register('project_browser.install_readiness')
        ->setClass(InstallReadiness::class)
        ->setArguments([
          new Reference('project_browser.installer'),
          new Reference('event_dispatcher'),
        ]);
      $container->register('project_browser.composer_validator.core_not_updated')
        ->setClass(CoreNotUpdatedValidator::class)
        ->addTag('event_subscriber')
        ->setArguments([
          new Reference(PathLocator::class),
          new Reference(ComposerInspector::class),
        ]);
      $container->register('project_browser.composer_validator.package_not_installed_validator')
        ->setClass(PackageNotInstalledValidator::class)
        ->addTag('event_subscriber')
        ->setArguments([
          new Reference(PathLocator::class),
          new Reference(ComposerInspector::class),
        ]);
    }

    // @todo Remove the following Drupal 10.0 autowiring shim in
    //   https://www.drupal.org/i/3349193.
    $autowire_aliases = [
      ConfigFactoryInterface::class => 'config.factory',
      FileSystemInterface::class => 'file_system',
      ModuleHandlerInterface::class => 'module_handler',
      StateInterface::class => 'state',
      ModuleExtensionList::class => 'extension.list.module',
      ThemeExtensionList::class => 'extension.list.theme',
      StreamWrapperManagerInterface::class => 'stream_wrapper_manager',
      Connection::class => 'database',
      QueueFactory::class => 'queue',
      PrivateKey::class => 'private_key',
    ];
    foreach ($autowire_aliases as $interface => $service_id) {
      if (!$container->hasAlias($interface)) {
        $container->setAlias($interface, $service_id);
      }
    }
  }

}
