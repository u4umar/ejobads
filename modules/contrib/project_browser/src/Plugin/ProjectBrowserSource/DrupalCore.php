<?php

namespace Drupal\project_browser\Plugin\ProjectBrowserSource;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\Extension;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Site\Settings;
use Drupal\project_browser\Plugin\ProjectBrowserSourceBase;
use Drupal\project_browser\ProjectBrowser\Project;
use Drupal\project_browser\ProjectBrowser\ProjectsResultsPage;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The source plugin to get Drupal core projects list.
 *
 * @ProjectBrowserSource(
 *   id = "drupal_core",
 *   label = @Translation("Drupal core"),
 *   description = @Translation("Gets core projects and filters information"),
 * )
 */
class DrupalCore extends ProjectBrowserSourceBase implements ContainerFactoryPluginInterface {

  /**
   * All core modules are covered under security policy.
   *
   * @var string
   */
  const COVERED = 'covered';

  /**
   * All core modules are "Active" modules.
   *
   * @var string
   */
  const ACTIVE = 'active';

  /**
   * All core modules are "Maintained" modules.
   *
   * @var string
   */
  const MAINTAINED = 'maintained';

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    private readonly CacheBackendInterface $cacheBin,
    private readonly ModuleExtensionList $moduleExtensionList,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('cache.project_browser'),
      $container->get('extension.list.module')
    );
  }

  /**
   * Filters module extension list for core modules.
   *
   * @return \Drupal\Core\Extension\Extension[]
   *   The array containing core modules, keyed by module machine name.
   */
  protected function getCoreModules() {
    $projects = array_filter($this->moduleExtensionList->reset()->getList(), fn(Extension $project) => $project->origin === 'core');
    $include_tests = Settings::get('extension_discovery_scan_tests') || drupal_valid_test_ua();
    if (!$include_tests) {
      $projects = array_filter($projects, fn(Extension $project) => empty($project->info['hidden']) && $project->info['package'] !== 'Testing');
    }
    return $projects;
  }

  /**
   * {@inheritdoc}
   */
  public function getCategories(): array {
    $categories = [];
    foreach ($this->getCoreModules() as $module) {
      $categories[$module->info['package']] = [
        'name' => $module->info['package'],
        'id' => $module->info['package'],
      ];
    }
    usort($categories, fn($a, $b) => $a['id'] <=> $b['id']);
    return $categories;
  }

  /**
   * {@inheritdoc}
   */
  public function getProjects(array $query = []) : ProjectsResultsPage {
    $projects = $this->getProjectData();

    // Filter by project machine name.
    if (!empty($query['machine_name'])) {
      $projects = array_filter($projects, fn(Project $project) => $project->getMachineName() === $query['machine_name']);
    }

    // Filter by coverage.
    if (!empty($query['security_advisory_coverage']) && $query['security_advisory_coverage'] === self::COVERED) {
      $projects = array_filter($projects, fn(Project $project) => $project->isCovered());
    }

    // Filter by categories.
    if (!empty($query['categories'])) {
      $projects = array_filter($projects, fn(Project $project) => array_intersect(array_column($project->getModuleCategories(), 'id'), explode(',', $query['categories'])));
    }

    // Filter by search text.
    if (!empty($query['search'])) {
      $projects = array_filter($projects, fn(Project $project) => stripos($project->getTitle(), $query['search']) !== FALSE);
    }

    // Filter by sorting criterion.
    if (!empty($query['sort'])) {
      $sort = $query['sort'];
      switch ($sort) {
        case 'a_z':
          usort($projects, fn($x, $y) => $x->getTitle() <=> $y->getTitle());
          break;

        case 'z_a':
          usort($projects, fn($x, $y) => $y->getTitle() <=> $x->getTitle());
          break;
      }
    }
    $project_count = count($projects);
    if (!empty($query['page']) && !empty($query['limit'])) {
      $projects = array_chunk($projects, $query['limit'])[$query['page']] ?? [];
    }
    return new ProjectsResultsPage($project_count, array_values($projects), (string) $this->getPluginDefinition()['label'], $this->getPluginId(), FALSE);
  }

  /**
   * Gets the project data from cache if available, or builds it if not.
   *
   * @return \Drupal\project_browser\ProjectBrowser\Project[]
   */
  protected function getProjectData(): array {
    $stored_projects = $this->cacheBin->get('DrupalCore:projects');
    if ($stored_projects) {
      return $stored_projects->data;
    }

    $returned_list = [];
    foreach ($this->getCoreModules() as $module_name => $module) {
      // Dummy data is used for the fields that are unavailable for core
      // modules.
      $returned_list[] = (new Project())
        ->setProjectStatus($module->status)
        ->setProjectTitle($module->info['name'])
        ->setMachineName($module_name)
        ->setLogo([
          'file' => [
            'uri' => \Drupal::request()->getSchemeAndHttpHost() . '/core/misc/logo/drupal-logo.svg',
            'resource' => 'image',
          ],
          'alt' => '',
        ])
        ->setAuthor([
          'name' => 'Drupal Core',
        ])
        ->setSummary([
          'summary' => $module->info['description'],
          'value' => $module->info['description'],
        ])
        ->setModuleCategories([
          [
            'id' => $module->info['package'],
            'name' => $module->info['package'],
          ],
        ])
        ->setCreatedTimestamp(280299600)
        ->setChangedTimestamp(280299600)
        ->setProjectUsageTotal(-1)
        ->setProjectStarUserCount(-1)
        ->setId(0)
        ->setComposerNamespace('')
        // All core projects are considered compatible.
        ->setIsCompatible(TRUE)
        ->setIsCovered($module->info['package'] !== 'Core (Experimental)')
        ->setIsActive(TRUE)
        ->setIsMaintained(TRUE);
    }

    $this->cacheBin->set('DrupalCore:projects', $returned_list);
    return $returned_list;
  }

  /**
   * {@inheritdoc}
   */
  public function getSortOptions(): array {
    return [
      'a_z' => [
        'id' => 'a_z',
        'text' => $this->t('A-Z'),
      ],
      'z_a' => [
        'id' => 'z_a',
        'text' => $this->t('Z-A'),
      ],
    ];
  }

}
