<?php

namespace Drupal\project_browser\Form;

use Drupal\Component\Plugin\Discovery\CachedDiscoveryInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RouteBuilderInterface;
use Drupal\project_browser\Plugin\ProjectBrowserSourceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Settings form for Project Browser.
 */
class SettingsForm extends ConfigFormBase {

  public function __construct(
    ConfigFactoryInterface $config_factory,
    private readonly ProjectBrowserSourceManager $manager,
    private readonly CacheBackendInterface $cacheBin,
    private readonly ModuleHandlerInterface $moduleHandler,
    private readonly RouteBuilderInterface $routeBuilder,
    private readonly CachedDiscoveryInterface $contextualLinkManager,
    private readonly CacheBackendInterface $renderCache,
  ) {
    parent::__construct($config_factory);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('plugin.manager.project_browser.source'),
      $container->get('cache.project_browser'),
      $container->get('module_handler'),
      $container->get('router.builder'),
      $container->get('plugin.manager.menu.contextual_link'),
      $container->get('cache.render')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'project_browser_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['project_browser.admin_settings'];
  }

  /**
   * Returns an array containing the table headers.
   *
   * @return array
   *   The table header.
   */
  protected function getTableHeader() {
    return [
      $this->t('Source'),
      $this->t('Status'),
      $this->t('Weight'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('project_browser.admin_settings');

    // Confirm that Package Manager is installed.
    $package_manager_not_ready = !$this->moduleHandler->moduleExists('package_manager');
    $form['allow_ui_install'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Allow installing via UI (experimental)'),
      '#default_value' => $config->get('allow_ui_install'),
      '#description' => $this->t('When enabled, modules can be downloaded and enabled via the Project Browser UI.'),
      '#disabled' => $package_manager_not_ready,
    ];

    $form['disable_add_new_module'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Disable "Add new module" page'),
      '#default_value' => $config->get('disable_add_new_module'),
      '#description' => $this->t('Removes access to the Drupal page that allows .tar modules to be installed, which does now use composer, so is strongly discouraged when using Project Browser.'),
    ];

    if ($package_manager_not_ready) {
      $form['allow_ui_install_compatibility'] = [
        '#type' => 'container',
        '#markup' => $this->t('The ability to install modules via the Project Browser UI requires Package Manager version 2.5 or newer. Package Manager is provided as part of the <a href="https://www.drupal.org/project/automatic_updates" target="_blank" rel="noopener noreferrer">Automatic Updates</a> module.'),
      ];
    }

    $source_plugins = $this->manager->getDefinitions();
    $enabled_sources = $config->get('enabled_sources');
    // Sort the source plugins by the order they're stored in config.
    $sorted_arr = array_merge(array_flip($enabled_sources), $source_plugins);
    $source_plugins = array_merge($sorted_arr, $source_plugins);

    $weight_delta = round(count($source_plugins) / 2);
    $table = [
      '#type' => 'table',
      '#header' => $this->getTableHeader(),
      '#empty' => $this->t('At least two source plugins are required to configure this feature.'),
      '#attributes' => [
        'id' => 'project_browser',
      ],
    ];
    $options = [
      'enabled' => $this->t('Enabled'),
      'disabled' => $this->t('Disabled'),
    ];
    if (count($source_plugins) > 1) {
      $form['#attached']['library'][] = 'project_browser/tabledrag';
      foreach ($options as $status => $title) {
        $table['#tabledrag'][] = [
          'action' => 'match',
          'relationship' => 'sibling',
          'group' => 'source-status-select',
          'subgroup' => 'source-status-' . $status,
          'hidden' => FALSE,
        ];
        $table['#tabledrag'][] = [
          'action' => 'order',
          'relationship' => 'sibling',
          'group' => 'source-weight',
          'subgroup' => 'source-weight-' . $status,
        ];
        $table['status-' . $status] = [
          '#attributes' => [
            'class' => ['status-title', 'status-title-' . $status],
            'no_striping' => TRUE,
          ],
        ];
        $table['status-' . $status]['title'] = [
          '#plain_text' => $title,
          '#wrapper_attributes' => [
            'colspan' => 3,
          ],
        ];

        // Plugin rows.
        foreach ($source_plugins as $plugin_name => $plugin_definition) {
          // Only include plugins in their respective section.
          if (($status === 'enabled') !== in_array($plugin_name, $enabled_sources, TRUE)) {
            continue;
          }

          $label = (string) $plugin_definition['label'];
          $plugin_key_exists = array_search($plugin_name, $enabled_sources);
          $table[$plugin_name] = [
            '#attributes' => [
              'class' => [
                'draggable',
              ],
            ],
          ];
          $table[$plugin_name]['source'] = [
            '#plain_text' => $label,
            '#wrapper_attributes' => [
              'id' => 'source--' . $plugin_name,
            ],
          ];

          $table[$plugin_name]['status'] = [
            '#type' => 'select',
            '#default_value' => $status,
            '#required' => TRUE,
            '#title' => $this->t('Status for @source source', ['@source' => $label]),
            '#title_display' => 'invisible',
            '#options' => $options,
            '#attributes' => [
              'class' => ['source-status-select', 'source-status-' . $status],
            ],
          ];
          $table[$plugin_name]['weight'] = [
            '#type' => 'weight',
            '#default_value' => ($plugin_key_exists === FALSE) ? 0 : $plugin_key_exists,
            '#delta' => $weight_delta,
            '#title' => $this->t('Weight for @source source', ['@source' => $label]),
            '#title_display' => 'invisible',
            '#attributes' => [
              'class' => ['source-weight', 'source-weight-' . $status],
            ],
          ];
        }
      }
    }

    $form['enabled_sources'] = $table;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $all_plugins = $form_state->getValue('enabled_sources');
    if (!array_key_exists('enabled', array_count_values(array_column($all_plugins, 'status')))) {
      $form_state->setErrorByName('enabled_sources', $this->t('At least one source plugin must be enabled.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $settings = $this->config('project_browser.admin_settings');
    $disable_old = $settings->get('disable_add_new_module');
    $disable_new = $form_state->getValue('disable_add_new_module');
    $all_plugins = $form_state->getValue('enabled_sources');
    $enabled_plugins = array_filter($all_plugins, fn($source) => $source['status'] === 'enabled');
    $settings
      ->set('enabled_sources', array_keys($enabled_plugins))
      ->set('allow_ui_install', $form_state->getValue('allow_ui_install'))
      ->set('disable_add_new_module', $disable_new)
      ->save();
    $this->cacheBin->deleteAll();
    if ($disable_old != $disable_new) {
      // If disable add new module changed, clear route and menu caches.
      $this->routeBuilder->rebuild();
      $this->contextualLinkManager->clearCachedDefinitions();
      $this->renderCache->invalidateAll();
    }
    parent::submitForm($form, $form_state);
  }

}
