<?php

namespace Drupal\project_browser_devel\Plugin\ProjectBrowserSource;

use Drupal\Component\Utility\Random;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\project_browser\Plugin\ProjectBrowserSourceBase;
use Drupal\project_browser\ProjectBrowser\Project;
use Drupal\project_browser\ProjectBrowser\ProjectsResultsPage;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Random data plugin. Used mostly for testing.
 *
 * To enable this source:
 * - `drush config:set project_browser.admin_settings enabled_source random_data`
 *
 * @ProjectBrowserSource(
 *   id = "random_data",
 *   label = @Translation("Random data"),
 *   description = @Translation("Gets random project and filters information"),
 * )
 */
class RandomDataPlugin extends ProjectBrowserSourceBase implements ContainerFactoryPluginInterface {

  /**
   * Utility to create random data.
   *
   * @var \Drupal\Component\Utility\Random
   */
  protected $randomGenerator;

  /**
   * ProjectBrowser cache bin.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cacheBin;

  /**
   * Constructs a MockDrupalDotOrg object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_bin
   *   The cache bin.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CacheBackendInterface $cache_bin) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->randomGenerator = new Random();
    $this->cacheBin = $cache_bin;
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
    );
  }

  /**
   * Generate random IDs and labels.
   *
   * @param int $array_length
   *   Length of the array to generate.
   *
   * @return array
   *   Array of random IDs and names.
   */
  protected function getRandomIdsAndNames($array_length = 4): array {
    $data = [];
    for ($i = 0; $i < $array_length; $i++) {
      $data[] = [
        'id' => uniqid(),
        'name' => ucwords($this->randomGenerator->word(rand(6, 10))),
      ];
    }

    return $data;
  }

  /**
   * Returns a random date.
   *
   * @return int
   *   Random timestamp.
   */
  protected function getRandomDate() {
    return rand(strtotime('2 years ago'), strtotime('today'));
  }

  /**
   * {@inheritdoc}
   */
  public function getCategories(): array {
    $stored_categories = $this->cacheBin->get('RandomData:categories');
    if ($stored_categories) {
      $categories = $stored_categories->data;
    }
    else {
      $categories = $this->getRandomIdsAndNames(20);
      $this->cacheBin->set('RandomData:categories', $categories);
    }
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

    // Filter by categories.
    if (!empty($query['categories'])) {
      $projects = array_filter($projects, fn(Project $project) => array_intersect(array_column($project->getModuleCategories(), 'id'), explode(',', $query['categories'])));
    }

    // Filter by search text.
    if (!empty($query['search'])) {
      $projects = array_filter($projects, fn(Project $project) => stripos($project->getTitle(), $query['search']) !== FALSE);
    }

    return new ProjectsResultsPage(count($projects), array_values($projects), (string) $this->getPluginDefinition()['label'], $this->getPluginId(), TRUE);
  }

  /**
   * Gets the project data from cache if available, or builds it if not.
   */
  private function getProjectData(): array {
    $stored_projects = $this->cacheBin->get('RandomData:projects');
    if ($stored_projects) {
      return $stored_projects->data;
    }

    $projects = [];
    $number_of_projects = rand(16, 36);
    $categories = $this->getCategories();
    $broken_image = 'https://image.not/found' . uniqid() . '.jpg';
    $good_image = 'https://picsum.photos/600/400';
    for ($i = 0; $i < $number_of_projects; $i++) {
      $machine_name = strtolower($this->randomGenerator->word(10));
      $project_images = [];
      if ($i !== 0) {
        $project_images[] = [
          'file' => [
            'uri' => str_replace(4, 5, $good_image),
            'resource' => 'image',
          ],
          'alt' => $machine_name . ' something',
        ];
        $project_images[] = [
          'file' => [
            'uri' => str_replace(4, 6, $good_image),
            'resource' => 'image',
          ],
          'alt' => $machine_name . ' another thing',
        ];
      }

      $projects[] = (new Project())
        ->setAuthor([
          'name' => $this->randomGenerator->word(10),
        ])
        ->setCreatedTimestamp($this->getRandomDate())
        ->setChangedTimestamp($this->getRandomDate())
        ->setProjectStatus(rand(0, 1))
        ->setProjectTitle(ucwords($machine_name))
        ->setId(uniqid())
        ->setSummary([
          'summary' => $this->randomGenerator->paragraphs(1),
          'value' => $this->randomGenerator->paragraphs(5),
        ])
        ->setLogo([
          'file' => [
            'uri' => ($i % 3) ? $good_image : $broken_image,
            'resource' => 'image',
          ],
          'alt' => $machine_name . ' logo',
        ])
        ->setImages($project_images)
        ->setModuleCategories([$categories[array_rand($categories)]])
        ->setMachineName($machine_name)
        ->setComposerNamespace('random/' . $machine_name)
        ->setIsCompatible((bool) ($i / 4))
        ->setProjectUsageTotal(rand(0, 100000))
        ->setProjectStarUserCount(rand(0, 100))
        ->setIsCovered((bool) rand(0, 1))
        ->setIsActive((bool) rand(0, 1))
        ->setIsMaintained((bool) rand(0, 1));
    }
    $this->cacheBin->set('RandomData:projects', $projects);
    return $projects;
  }

}
