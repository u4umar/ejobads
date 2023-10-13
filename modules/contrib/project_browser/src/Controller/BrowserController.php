<?php

namespace Drupal\project_browser\Controller;

// cspell:ignore ctools

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\InfoParserException;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\project_browser\EnabledSourceHandler;
use Drupal\project_browser\InstallReadiness;
use Drupal\project_browser\Plugin\ProjectBrowserSourceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Defines a controller to provide the Project Browser UI.
 *
 * @internal
 *   Controller classes are internal.
 */
class BrowserController extends ControllerBase {

  public function __construct(
    private readonly ModuleExtensionList $moduleList,
    private readonly RequestStack $requestStack,
    private readonly EnabledSourceHandler $enabledSource,
    private readonly InstallReadiness|NULL $installReadiness,
  ) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('extension.list.module'),
      $container->get('request_stack'),
      $container->get('project_browser.enabled_source'),
      $container->has('project_browser.install_readiness') ? $container->get('project_browser.install_readiness') : NULL,
    );
  }

  /**
   * Builds the browse page and the individual module page.
   *
   * For routes without any module name, default browse page is rendered with
   * all the available modules.
   * For example, 'https//drupal-site/admin/modules/browse'.
   * And for module specific paths, the respective detailed module page is
   * rendered. For example, 'https//drupal-site/admin/modules/browse/ctools'
   * will display the details for ctools.
   *
   * @param string $module_name
   *   Module for which the detailed page is built.
   *
   * @return array
   *   A render array.
   */
  public function browse($module_name) {
    $modules_status = $this->getModuleStatuses();
    $request = $this->requestStack->getCurrentRequest();
    $current_sources = $this->enabledSource->getCurrentSources();
    $ui_install_enabled = (bool) $this->config('project_browser.admin_settings')->get('allow_ui_install') && (bool) $this->installReadiness;

    if (!empty($current_sources['drupalorg_mockapi']) && !$module_name) {
      $this->messenger()
        ->addStatus($this->t('Project Browser is currently a prototype, and the projects listed may not be up to date with Drupal.org. For the most updated list of projects, please visit <a href=":url">:url</a>', [':url' => 'https://www.drupal.org/project/project_module']))
        ->addStatus($this->t('Your feedback and input are welcome at <a href=":url">:url</a>', [':url' => 'https://www.drupal.org/project/issues/project_browser']));
    }

    $current_sources_keys = array_keys($current_sources);
    // To get common data from single source plugin.
    $current_source = reset($current_sources);

    $sort_options = $active_plugins = [];
    foreach ($current_sources as $source) {
      $sort_options[$source->getPluginId()] = array_values($source->getSortOptions());
      $active_plugins[$source->getPluginId()] = $source->getPluginDefinition()['label'];
    }

    return [
      '#theme' => 'project_browser_main_app',
      '#attached' => [
        'library' => [
          'project_browser/svelte',
        ],
        'drupalSettings' => [
          'project_browser' => [
            'active_plugins' => $active_plugins,
            'modules' => $modules_status,
            'drupal_version' => \Drupal::VERSION,
            'drupal_core_compatibility' => \Drupal::CORE_COMPATIBILITY,
            'module_path' => $this->moduleHandler()->getModule('project_browser')->getPath(),
            'origin_url' => $request->getSchemeAndHttpHost() . $request->getBaseUrl(),
            'special_ids' => $this->getSpecialIds(),
            'sort_options' => $sort_options,
            'maintenance_options' => $current_source->getMaintenanceOptions(),
            'security_options' => $current_source->getSecurityOptions(),
            'development_options' => $current_source->getDevelopmentOptions(),
            'default_plugin_id' => $current_source->getPluginId(),
            'current_sources_keys' => $current_sources_keys,
            'ui_install' => $ui_install_enabled,
            'stage_available' => $ui_install_enabled ? $this->installReadiness->installerAvailable() : FALSE,
            'pm_validation' => $ui_install_enabled ? $this->installReadiness->validatePackageManager() : TRUE,
          ],
        ],
      ],
    ];
  }

  /**
   * Return special IDs for some vocabularies.
   *
   * This is needed because these two vocabularies have a special term
   * in them that shows an icon next to the label, so we need to be
   * explicit about these special cases.
   *
   * @return array
   *   List of special IDs per vocabulary.
   */
  protected function getSpecialIds(): array {
    return [
      'maintenance_status' => [
        'id' => ProjectBrowserSourceBase::MAINTAINED_ID,
        'name' => $this->t('@maintained_label', ['@maintained_label' => ProjectBrowserSourceBase::MAINTAINED_LABEL]),
      ],
      'security_coverage' => [
        'id' => ProjectBrowserSourceBase::COVERED_ID,
        'name' => $this->t('@covered_label', ['@covered_label' => ProjectBrowserSourceBase::COVERED_LABEL]),
      ],
      'all_values' => ProjectBrowserSourceBase::ALL_VALUES_ID,
    ];
  }

  /**
   * Gets all module statuses.
   *
   * @return array
   *   An array of module statues, keyed by machine name.
   */
  protected function getModuleStatuses(): array {
    // Sort all modules by their names.
    try {
      // The module list needs to be reset so that it can re-scan and include
      // any new modules that may have been added directly into the filesystem.
      $modules = $this->moduleList->reset()->getList();
      uasort($modules, [ModuleExtensionList::class, 'sortByName']);
    }
    catch (InfoParserException $e) {
      $this->messenger()->addError($this->t('Modules could not be listed due to an error: %error', ['%error' => $e->getMessage()]));
      $modules = [];
    }

    return array_map(function ($value) {
      return $value->status;
    }, $modules);
  }

}
