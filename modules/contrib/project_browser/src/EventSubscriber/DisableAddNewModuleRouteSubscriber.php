<?php

namespace Drupal\project_browser\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Route subscriber to disable "Add new module" page.
 */
class DisableAddNewModuleRouteSubscriber extends RouteSubscriberBase {

  /**
   * DisableAddNewModuleRouteSubscriber constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   */
  public function __construct(
    protected ConfigFactoryInterface $configFactory
  ) {}

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    $config = $this->configFactory->get('project_browser.admin_settings');
    if ($config->get('disable_add_new_module') && $route = $collection->get('update.module_install')) {
      $route->setRequirement('_access', 'FALSE');
    }
  }

}
