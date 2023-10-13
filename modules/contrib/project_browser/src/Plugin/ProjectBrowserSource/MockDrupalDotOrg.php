<?php

namespace Drupal\project_browser\Plugin\ProjectBrowserSource;

use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\Html;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\State\StateInterface;
use Drupal\project_browser\Plugin\ProjectBrowserSourceBase;
use Drupal\project_browser\ProjectBrowser\Project;
use Drupal\project_browser\ProjectBrowser\ProjectsResultsPage;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\TransferStats;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The source that mocks the Drupal.org API that's still to-be-built.
 *
 * To enable this source (this is the default for the module):
 * - `drush config:set --input-format=yaml project_browser.admin_settings enabled_sources '[drupalorg_mockapi]'`
 *
 * @ProjectBrowserSource(
 *   id = "drupalorg_mockapi",
 *   label = @Translation("Drupal.org (mocked)"),
 *   description = @Translation("Gets project and filters information from a mock API"),
 * )
 */
class MockDrupalDotOrg extends ProjectBrowserSourceBase implements ContainerFactoryPluginInterface {

  /**
   * This is what the Mock understands as "Covered" modules.
   *
   * @var array
   */
  const COVERED_VALUES = ['covered'];

  /**
   * This is what the Mock understands as "Active" modules.
   *
   * @var array
   */
  const ACTIVE_VALUES = [9988, 13030];

  /**
   * This is what the Mock understands as "Maintained" modules.
   *
   * @var array
   */
  const MAINTAINED_VALUES = [13028, 19370, 9990];

  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    private readonly LoggerInterface $logger,
    private readonly Connection $database,
    private readonly ClientInterface $httpClient,
    private readonly StateInterface $state,
    private readonly CacheBackendInterface $cacheBin,
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
      $container->get('logger.factory')->get('project_browser'),
      $container->get('database'),
      $container->get('http_client'),
      $container->get('state'),
      $container->get('cache.project_browser'),
    );
  }

  /**
   * Gets status vocabulary info from the Drupal.org json endpoint.
   *
   * @param int $taxonomy_id
   *   The id of the taxonomy being retrieved.
   *
   * @return array|array[]
   *   An array with the term id, name and description.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   *   Thrown if request is unsuccessful.
   */
  protected function getStatuses(int $taxonomy_id) {
    $cached_statuses = $this->cacheBin->get("MockDrupalDotOrg:taxonomy_$taxonomy_id");
    if ($cached_statuses) {
      return $cached_statuses->data;
    }
    $url = "https://www.drupal.org/api-d7/taxonomy_term.json?vocabulary=$taxonomy_id";
    $response = $this->httpClient->request('GET', $url);
    if ($response->getStatusCode() !== 200) {
      throw new \RuntimeException("Request to $url failed, returned {$response->getStatusCode()} with reason: {$response->getReasonPhrase()}");
    }
    $body = Json::decode($response->getBody()->getContents());
    $list = $body['list'];
    $list = array_map(function ($item) {
      $item['id'] = $item['tid'];
      return array_intersect_key($item, array_flip(['id', 'name', 'description']));
    }, $list);
    $this->cacheBin->set("MockDrupalDotOrg:taxonomy_$taxonomy_id", $list);

    return $list;
  }

  /**
   * {@inheritdoc}
   */
  protected function getDevelopmentStatuses(): array {
    return $this->getStatuses(46);
  }

  /**
   * {@inheritdoc}
   */
  public function getSortOptions(): array {
    return array_diff_key(parent::getSortOptions(), ['best_match' => '']);
  }

  /**
   * {@inheritdoc}
   */
  protected function getMaintenanceStatuses(): array {
    return $this->getStatuses(44);
  }

  /**
   * {@inheritdoc}
   */
  protected function getSecurityCoverages(): array {
    return [
      ['id' => 'covered', 'name' => 'Covered'],
      ['id' => 'not-covered', 'name' => 'Not covered'],
    ];
  }

  /**
   * Convert the sort entry within the query from received to expected by DB.
   *
   * @param array $query
   *   Query array to transform.
   */
  protected function convertSort(array &$query) {
    if (!empty($query['sort'])) {
      $options_available = $this->getSortOptions();
      if (!in_array($query['sort'], array_keys($options_available))) {
        unset($query['sort']);
      }
      else {
        // Valid value.
        switch ($query['sort']) {
          case 'usage_total':
          case 'best_match':
            $query['sort'] = 'project_usage_total';
            $query['direction'] = 'DESC';
            break;

          case 'a_z':
            $query['sort'] = 'title';
            $query['direction'] = 'ASC';
            break;

          case 'z_a':
            $query['sort'] = 'title';
            $query['direction'] = 'DESC';
            break;

          case 'created':
            $query['sort'] = 'created';
            $query['direction'] = 'DESC';
            break;

        }
      }
    }
  }

  /**
   * Convert the maintenance entry within the query from received to expected by DB.
   *
   * @param array $query
   *   Query array to transform.
   */
  protected function convertMaintenance(array &$query) {
    if (!empty($query['maintenance_status'])) {
      $options_available = $this->getMaintenanceOptions();
      if (!in_array($query['maintenance_status'], array_keys($options_available))) {
        unset($query['maintenance_status']);
      }
      else {
        // Valid value.
        switch ($query['maintenance_status']) {
          case self::MAINTAINED_ID:
            $query['maintenance_status'] = self::MAINTAINED_VALUES;
            break;

          case 'all':
            unset($query['maintenance_status']);
            break;

        }
      }
    }
  }

  /**
   * Convert the development entry within the query from received to expected by DB.
   *
   * @param array $query
   *   Query array to transform.
   */
  protected function convertDevelopment(array &$query) {
    if (!empty($query['development_status'])) {
      $options_available = $this->getDevelopmentOptions();
      if (!in_array($query['development_status'], array_keys($options_available))) {
        unset($query['development_status']);
      }
      else {
        // Valid value.
        switch ($query['development_status']) {
          case self::ACTIVE_ID:
            $query['development_status'] = self::ACTIVE_VALUES;
            break;

          case 'all':
            unset($query['development_status']);
            break;

        }
      }
    }
  }

  /**
   * Convert the security entry within the query from received to expected by DB.
   *
   * @param array $query
   *   Query array to transform.
   */
  protected function convertSecurity(array &$query) {
    if (!empty($query['security_advisory_coverage'])) {
      $options_available = $this->getSecurityOptions();
      if (!in_array($query['security_advisory_coverage'], array_keys($options_available))) {
        unset($query['security_advisory_coverage']);
      }
      else {
        // Valid value.
        switch ($query['security_advisory_coverage']) {
          case self::COVERED_ID:
            $query['security_advisory_coverage'] = self::COVERED_VALUES;
            break;

          case 'all':
            $keys = [];
            $options = $this->getSecurityCoverages();
            foreach ($options as $option) {
              $keys[] = $option['id'];
            }
            $query['security_advisory_coverage'] = $keys;
            break;

        }
      }
    }
  }

  /**
   * Convert the search values from available ones to expected ones.
   *
   * The values that were given as available for the search need to be the
   * actual values that will be queried within the search function.
   *
   * @param array $query
   *   Query parameters to check.
   *
   * @return array
   *   Query parameters converted to the values expected by the search function.
   */
  protected function convertQueryOptions(array $query = []): array {
    $this->convertSort($query);
    $this->convertMaintenance($query);
    $this->convertDevelopment($query);
    $this->convertSecurity($query);

    return $query;
  }

  /**
   * Returns category data keyed by category ID.
   *
   * @return array
   *   The category ID and name, keyed by ID.
   */
  protected function getCategoryData(): array {
    $module_path = \Drupal::service('module_handler')->getModule('project_browser')->getPath();
    $category_list = Json::decode(file_get_contents($module_path . '/fixtures/category_list.json')) ?? [];
    $categories = [];
    foreach ($category_list as $category) {
      $categories[$category['tid']] = [
        'id' => $category['tid'],
        'name' => $category['name'],
      ];
    }
    return $categories;
  }

  /**
   * {@inheritdoc}
   */
  public function getCategories(): array {
    // Rekey the array to avoid JSON considering it an object.
    return array_values($this->getCategoryData());
  }

  /**
   * {@inheritdoc}
   */
  public function getProjects(array $query = []) : ProjectsResultsPage {
    $api_response = $this->fetchProjects($query);
    $categories = $this->getCategoryData();

    $returned_list = [];
    if ($api_response) {
      foreach ($api_response['list'] as $project_data) {
        $avatar_url = 'https://git.drupalcode.org/project/' . $project_data['field_project_machine_name'] . '/-/avatar';
        $logo = [
          'file' => [
            'uri' => $avatar_url,
            'resource' => 'image',
          ],
          'alt' => 'Project logo',
        ];

        $returned_list[] = (new Project())
          ->setId($project_data['nid'])
          ->setProjectTitle($project_data['title'])
          ->setProjectStatus($project_data['status'])
          ->setProjectUrl('https://www.drupal.org/project/' . $project_data['field_project_machine_name'])
          ->setChangedTimestamp($project_data['changed'])
          ->setCreatedTimestamp($project_data['created'])
          ->setAuthor(['name' => $project_data['author']])
          // Add name property to each category, so it can be rendered.
          ->setModuleCategories(array_map(fn($category) => $categories[$category['id']] ?? '', $project_data['project_data']['taxonomy_vocabulary_3'] ?? []))
          // Mock projects are filtered and made sure that they are compatible
          // before we even put them in the database.
          ->setIsCompatible(TRUE)
          ->setProjectUsageTotal(array_reduce($project_data['project_data']['project_usage'] ?? [], fn($total, $project_usage) => $total + $project_usage) ?: 0)
          ->setLogo($logo)
          ->setImages($project_data['project_data']['field_project_images'] ?? [])
          ->setSummary($this->relativeToAbsoluteUrls($project_data['project_data']['body'], 'https://www.drupal.org'))
          ->setIsCovered(in_array($project_data['field_security_advisory_coverage'], self::COVERED_VALUES))
          ->setIsActive(in_array($project_data['development_status'], self::ACTIVE_VALUES))
          ->setIsMaintained(in_array($project_data['maintenance_status'], self::MAINTAINED_VALUES))
          ->setWarnings($this->getWarnings($project_data))
          ->setMachineName($project_data['field_project_machine_name'])
          ->setComposerNamespace('drupal/' . $project_data['field_project_machine_name'])
          ->setProjectStarUserCount(-1);
      }
    }

    return new ProjectsResultsPage($api_response['total_results'] ?? 0, $returned_list, (string) $this->getPluginDefinition()['label'], $this->getPluginId(), TRUE);
  }

  /**
   * Fetches the projects from the mock backend.
   *
   * Here, we're querying the local database, populated from the fixture.
   * Ultimately, in the real implementation, this would be fetching over
   * the Drupal.org (JSON?) API (TBD).
   */
  protected function fetchProjects($query) {
    $query = $this->convertQueryOptions($query);
    try {
      $db_query = $this->database->select('project_browser_projects', 'pbp')
        ->fields('pbp')
        ->condition('pbp.status', 1);

      if (array_key_exists('machine_name', $query)) {
        $db_query->condition('field_project_machine_name', $query['machine_name']);
      }

      if (array_key_exists('sort', $query) && !empty($query['sort'])) {
        $sort = $query['sort'];
        $direction = (array_key_exists('direction', $query) && $query['direction'] == 'ASC') ? 'ASC' : 'DESC';
        $db_query->orderBy($sort, $direction);
      }
      else {
        // Default order.
        $db_query->orderBy('project_usage_total', 'DESC');
      }

      // Filter by maintenance status.
      if (array_key_exists('maintenance_status', $query)) {
        $db_query->condition('maintenance_status', $query['maintenance_status'], 'IN');
      }

      // Filter by development status.
      if (array_key_exists('development_status', $query)) {
        $db_query->condition('development_status', $query['development_status'], 'IN');
      }

      // Filter by security advisory coverage.
      if (array_key_exists('security_advisory_coverage', $query)) {
        $db_query->condition('field_security_advisory_coverage', $query['security_advisory_coverage'], 'IN');
      }

      // Filter by category.
      if (!empty($query['categories'])) {
        $tids = explode(',', $query['categories']);
        $db_query->join('project_browser_categories', 'cat', 'pbp.nid = cat.pid');
        $db_query->condition('cat.tid', $tids, 'IN');
      }

      // Filter by search term.
      if (array_key_exists('search', $query)) {
        $search = $query['search'];
        $db_query->condition('pbp.project_data', "%$search%", 'LIKE');
      }
      $db_query->groupBy('pbp.nid');

      // If there is a specified limit, then this is a list of multiple
      // projects.
      $total_results = $db_query->countQuery()
        ->execute()
        ->fetchField();
      $offset = $query['page'] ?? 0;
      $limit = $query['limit'] ?? 50;
      $db_query->range($limit * $offset, $limit);
      $result = $db_query
        ->execute()
        ->fetchAll();
      $db_projects = array_map(function ($project_data) {
        $data = (array) $project_data;
        $data['project_data'] = unserialize($project_data->project_data);
        return $data;
      }, $result);

      if (count($db_projects) > 0) {
        $drupal_org_response['list'] = $db_projects;
        $drupal_org_response['total_results'] = $total_results;
        return $drupal_org_response;
      }

      return FALSE;
    }
    catch (\Exception $exception) {
      $this->logger->error($exception->getMessage());
      return FALSE;
    }
  }

  /**
   * Determines warning messages based on development and maintenance status.
   *
   * @param $project
   *   A project array.
   *
   * @return string[]
   *   An array of warning messages.
   */
  protected function getWarnings($project) {
    // This is based on logic from Drupal.org.
    // @see https://git.drupalcode.org/project/drupalorg/-/blob/e31465608d1380345834/drupalorg_project/drupalorg_project.module
    $warnings = [];
    $merged_vocabularies = array_merge($this->getDevelopmentStatuses(), $this->getMaintenanceStatuses());
    $statuses = array_column($merged_vocabularies, 'description', 'id');
    foreach (['taxonomy_vocabulary_44', 'taxonomy_vocabulary_46'] as $field) {
      // Maintenance status is not Actively maintained and Development status is
      // not Under active development.
      $id = $project[$field]['id'] ?? FALSE;
      if ($id && !in_array($id, [13028, 9988])) {
        // Maintenance status is Abandoned, or Development status is No further
        // development or Obsolete.
        if (in_array($id, [13032, 16538, 9994])) {
          $warnings[] = $statuses[$id];
        }
      }
    }
    return $warnings;
  }

  /**
   * Convert relative URLs found in the body to absolute URLs.
   *
   * @param array $body
   *   Body array field containing summary and value properties.
   * @param string $base_url
   *   Base URL to prepend to relative links.
   *
   * @return array
   *   Body array with relative URLs converted to absolute ones.
   */
  protected function relativeToAbsoluteUrls(array $body, string $base_url) {
    if (empty($body['value'])) {
      $body['value'] = $body['summary'] ?? '';
    }
    $body['value'] = Html::transformRootRelativeUrlsToAbsolute($body['value'], $base_url);
    return $body;
  }

  /**
   * Checks if a project's security coverage has been revoked.
   *
   * @param string $project_id
   *   The project id.
   *
   * @return bool
   *   False if the project's security coverage is revoked, otherwise true.
   */
  public function isProjectSafe(string $project_id): bool {
    try {
      $response = $this->httpClient->request('GET', "https://www.drupal.org/api-d7/node.json", [
        'on_stats' => static function (TransferStats $stats) use (&$url) {
          // phpcs:ignore DrupalPractice.CodeAnalysis.VariableAnalysis.UnusedVariable
          $url = $stats->getEffectiveUri();
        },
        'query' => ['field_project_machine_name' => $project_id],
      ]);
    }
    catch (RequestException $re) {
      // Try a second time because sometimes d.o times out the request.
      $response = $this->httpClient->request('GET', "https://www.drupal.org/api-d7/node.json", [
        'on_stats' => static function (TransferStats $stats) use (&$url) {
          // phpcs:ignore DrupalPractice.CodeAnalysis.VariableAnalysis.UnusedVariable
          $url = $stats->getEffectiveUri();
        },
        'query' => ['field_project_machine_name' => $project_id],
      ]);
    }
    if ($response->getStatusCode() !== 200) {
      throw new \RuntimeException("Request to $url failed, returned {$response->getStatusCode()} with reason: {$response->getReasonPhrase()}");
    }

    $project_info = Json::decode($response->getBody()->getContents());
    if (isset($project_info['list'][0]['field_security_advisory_coverage']) && count($project_info['list']) === 1) {
      return $project_info['list'][0]['field_security_advisory_coverage'] !== 'revoked';
    }

    return FALSE;
  }

}
