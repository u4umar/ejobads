<?php

namespace Drupal\project_browser\Form;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\project_browser\Plugin\ProjectBrowserSourceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Settings form for Project Browser.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * The Project Browser Source Manager.
   *
   * @var \Drupal\project_browser\Plugin\ProjectBrowserSourceManager
   */
  protected $manager;

  /**
   * ProjectBrowser cache bin.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cacheBin;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Constructs a \Drupal\project_browser\Form\SettingsForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\project_browser\Plugin\ProjectBrowserSourceManager $manager
   *   The plugin manager.
   * @param \Drupal\Core\Cache\CacheBackendInterface $project_browser_cache
   *   The cache bin.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ProjectBrowserSourceManager $manager, CacheBackendInterface $project_browser_cache, ModuleHandlerInterface $module_handler) {
    parent::__construct($config_factory);
    $this->manager = $manager;
    $this->cacheBin = $project_browser_cache;
    $this->moduleHandler = $module_handler;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('plugin.manager.project_browser.source'),
      $container->get('cache.project_browser'),
      $container->get('module_handler')
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

    // Confirm that package manager is installed and that it provides the
    // CollectIgnoredPathsEvent, added in Package Manager 2.5. The event is
    // required by the UI install feature, so we check for its presence in
    // addition to the module being installed.
    $package_manager_not_ready = !$this->moduleHandler->moduleExists('package_manager') || !class_exists('\Drupal\package_manager\Event\CollectIgnoredPathsEvent');
    $form['allow_ui_install'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Allow installing via UI (experimental)'),
      '#default_value' => $config->get('allow_ui_install'),
      '#description' => $this->t('When enabled, modules can be downloaded and enabled via the Project Browser UI.'),
      '#disabled' => $package_manager_not_ready,
    ];

    if ($package_manager_not_ready) {
      $form['allow_ui_install_compatiblity'] = [
        '#type' => 'container',
        '#markup' => $this->t('The ability to install modules via the Project Browser UI requires Package Manager version 2.5 or newer. Package Manager is provided as part of the Automatic Updates module.'),
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
    $all_plugins = $form_state->getValue('enabled_sources');
    $enabled_plugins = array_filter($all_plugins, fn($source) => $source['status'] === 'enabled');
    $this->config('project_browser.admin_settings')
      ->set('enabled_sources', array_keys($enabled_plugins))
      ->set('allow_ui_install', $form_state->getValue('allow_ui_install'))
      ->save();
    $this->cacheBin->deleteAll();
    parent::submitForm($form, $form_state);
  }

}
