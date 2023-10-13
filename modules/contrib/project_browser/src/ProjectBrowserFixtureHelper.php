<?php

namespace Drupal\project_browser;

// cspell:ignore acquia

use GuzzleHttp\ClientInterface;
use Composer\Semver\Semver;
use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\Unicode;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Database\Connection;
use Drupal\Core\State\StateInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\TransferStats;

/**
 * Provides methods to populate a local database from a fixture.
 */
class ProjectBrowserFixtureHelper {

  public function __construct(
    private readonly Connection $connection,
    private readonly StateInterface $state,
    private readonly ClientInterface $httpClient,
  ) {}

  /**
   * Inserts data into Project Browser module tables.
   */
  public function populateFromFixture(string $path_to_data, string $path_to_categories, bool $truncate_first = TRUE): void {
    if ($truncate_first) {
      $this->connection->truncate('project_browser_projects')->execute();
      $this->connection->truncate('project_browser_categories')->execute();
    }

    $most_recent_change = 0;

    $projects = Json::decode(file_get_contents($path_to_data));
    $projects_chunk = array_chunk($projects, 1000);
    foreach ($projects_chunk as $chunk_projects) {
      // Insert fixture data to the database.
      $query = $this->connection->insert('project_browser_projects')->fields([
        'nid',
        'title',
        'author',
        'created',
        'changed',
        'project_usage_total',
        'maintenance_status',
        'development_status',
        'status',
        'field_security_advisory_coverage',
        'flag_project_star_user_count',
        'field_project_type',
        'project_data',
        'field_project_machine_name',
      ]);
      foreach ($chunk_projects as $project) {
        $project['title'] = static::whiteSpaceTrim($project['title']);
        if ($project['changed'] > $most_recent_change) {
          $most_recent_change = $project['changed'];
        }
        // Map from fixture format to the expected by the database.
        $project_data = is_array($project['project_data']) ? $project['project_data'] : unserialize($project['project_data']);
        $project['maintenance_status'] = $project_data['taxonomy_vocabulary_44']['id'];
        $project['development_status'] = $project_data['taxonomy_vocabulary_46']['id'];
        $project['field_project_machine_name'] = $project_data['field_project_machine_name'];

        $query->values($project);
      }
      $query->execute();
    }

    $categories = Json::decode(file_get_contents($path_to_categories));
    $category_query = $this->connection->insert('project_browser_categories')->fields([
      'tid',
      'pid',
    ]);
    foreach ($categories as $category) {
      $category_query->values((array) $category);
    }
    $category_query->execute();

    $this->state->set('project_browser.last_imported', $most_recent_change);
  }

  /**
   * Removes all sorts of whitespace and non-printable characters from a string.
   *
   * @param string $value
   *   Value to trim.
   *
   * @return string
   *   Trimmed value.
   *
   * @see https://www.drupal.org/project/search_api_trim_whitespace
   */
  private static function whiteSpaceTrim(string $value): string {
    $value = str_replace("&nbsp;", ' ', $value);

    // Remove multiple spaces.
    $value = preg_replace('/( {2,})+/imu', ' ', $value);

    // Remove spaces before punctuation.
    $value = preg_replace('/\s+([!?.,])/imu', "$1", $value);

    // Remove any space at the start of a string.
    $value = preg_replace('/^\s+/imu', '', $value);

    // Remove any non-printable characters.
    $value = preg_replace('/[[:^print:]]/imu', '', $value);

    return trim($value);
  }

  /**
   * Update the database with any projects updated since the last update.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   *
   * @throws \JsonException
   */
  public function updateMostRecentChanges(): void {
    $last_changed = $this->state->get('project_browser.last_imported');
    $add_to_db = [];
    $page = 0;

    // Start with a timestamp absurdly far into the future so the while loop
    // can initiate before $project_was_changed is updated with an actual
    // timestamp related to the project.
    $project_was_changed = 2583878026;
    while ($last_changed <= $project_was_changed) {
      $projects = $this->getProjectsFromSource([
        'page' => $page,
        'field_project_type' => 'full',
        'limit' => 50,
        'field_project_has_releases' => 1,
        'field_project_has_issue_queue' => 1,
        'type' => 'project_module',
        'status' => 1,
        'sort' => 'changed',
        'direction' => 'DESC',
      ]);
      foreach ($projects['list'] as $project) {
        $project_was_changed = $project['changed'];
        if ($project_was_changed < $last_changed) {
          break;
        }
        $releases = $this->getProjectReleasesFromSource($project['field_project_machine_name']);
        if (!empty($releases['releases'])) {
          $compatible_releases = array_filter($releases['releases'], function ($release) {
            if (!empty($release['core_compatibility'])) {
              try {
                // Wrap in try{} due to projects using invalid version strings.
                return Semver::satisfies(\Drupal::VERSION, $release['core_compatibility']);
              }
              catch (\Exception $exception) {
                return FALSE;
              }
            }
          });
          if (!empty($compatible_releases)) {
            $project['releases'] = array_map(function ($release) {
              return [
                'version' => $release['version'],
                'status' => $release['status'],
                'date' => $release['date'],
                'core_compatibility' => $release['core_compatibility'],
              ];
            }, $compatible_releases);
            $add_to_db[] = $project;
          }
        }
      }
      $page += 1;
    }
    foreach ($add_to_db as $project_to_update) {
      $this->truncateProjectData($project_to_update);
      $new_values = [
        'nid' => $project_to_update['nid'],
        'title' => trim($project_to_update['title']),
        'author' => (string) @$project_to_update['author']['name'],
        'created' => $project_to_update['created'],
        'changed' => $project_to_update['changed'],
        'project_usage_total' => $project_to_update['project_usage_total'] ?? 0,
        'maintenance_status' => $project_to_update['taxonomy_vocabulary_44']['id'],
        'development_status' => $project_to_update['taxonomy_vocabulary_46']['id'],
        'status' => $project_to_update['status'],
        'field_security_advisory_coverage' => $project_to_update['field_security_advisory_coverage'],
        'flag_project_star_user_count' => $project_to_update['flag_project_star_user_count'] ?? 0,
        'field_project_type' => $project_to_update['field_project_type'] ?? '',
        'project_data' => serialize($project_to_update),
      ];

      $result = $this->connection->select('project_browser_projects', 'pbp')
        ->fields('pbp')
        ->condition('pbp.nid', $project_to_update['nid'])
        ->execute();
      if (!empty($result->fetchAll())) {
        $this->connection->update('project_browser_projects')
          ->fields($new_values)
          ->condition('nid', $project_to_update['nid'])
          ->execute();
      }
      else {
        $this->connection->insert('project_browser_projects')
          ->fields($new_values)
          ->execute();
      }

      $category_values = [];
      if (!empty($project_to_update['taxonomy_vocabulary_3'])) {
        foreach ($project_to_update['taxonomy_vocabulary_3'] as $category) {
          $result = $this->connection->query("SELECT * FROM {project_browser_categories} WHERE tid = :tid AND pid = :pid", [
            ':tid' => $category['id'],
            ':pid' => $project_to_update['nid'],
          ]);
          if (empty($result->fetchAll())) {
            $category_values[] = [
              'tid' => $category['id'],
              'pid' => $project_to_update['nid'],
            ];
          }
        }
      }
      if (!empty($category_values)) {
        $category_query = $this->connection->insert('project_browser_categories')
          ->fields(['tid', 'pid']);
        foreach ($category_values as $record) {
          $category_query->values($record);
        }
        $category_query->execute();
      }
    }
    $this->state->set('project_browser.last_imported', time());
  }

  /**
   * Strip project data of unnecessary items.
   *
   * @param array $project
   *   Data for a project.
   */
  private function truncateProjectData(array &$project): void {
    if (!empty($project['field_project_images'])) {
      $project['field_project_images'] = [$project['field_project_images'][0]];
    }
    unset($project['flag_project_star_user']);
    unset($project['field_supporting_organizations']);
    unset($project['url']);
    unset($project['author']['uri']);
    unset($project['author']['id']);
    unset($project['author']['resource']);
    foreach ($project as $key => $value) {
      if (strpos($key, "\0*\0") !== FALSE) {
        if (strpos($key, 'field_project_type') !== FALSE) {
          $project['field_project_type'] = $value;
        }
        unset($project[$key]);
      }
    }
    foreach (['taxonomy_vocabulary_44', 'taxonomy_vocabulary_46'] as $value) {
      if (isset($project[$value])) {
        unset($project[$value]['uri']);
        unset($project[$value]['resource']);
      }
    }

    if (isset($project['taxonomy_vocabulary_3'])) {
      foreach ($project['taxonomy_vocabulary_3'] as $key => $value) {
        unset($project['taxonomy_vocabulary_3'][$key]['uri']);
        unset($project['taxonomy_vocabulary_3'][$key]['resource']);
      }
    }
  }

  /**
   * Get a list of all Drupal.org nodes of type 'project_module'.
   *
   * @param array $query
   *   An array of query parameters. See https://www.drupal.org/i/3218285.
   *
   * @return array
   *   An array of project data.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   * @throws \JsonException
   *
   * @see https://www.drupal.org/drupalorg/docs/apis/rest-and-other-apis
   */
  private function getProjectsFromSource(array $query = []): array {
    try {
      $response = $this->httpClient->request('GET', "https://www.drupal.org/api-d7/node.json", [
        'on_stats' => static function (TransferStats $stats) use (&$url) {
          // phpcs:ignore DrupalPractice.CodeAnalysis.VariableAnalysis.UnusedVariable
          $url = $stats->getEffectiveUri();
        },
        'query' => $query,
      ]);
    }
    catch (RequestException $re) {
      // Try a second time because sometimes d.o times out the request.
      $response = $this->httpClient->request('GET', "https://www.drupal.org/api-d7/node.json", [
        'on_stats' => static function (TransferStats $stats) use (&$url) {
          // phpcs:ignore DrupalPractice.CodeAnalysis.VariableAnalysis.UnusedVariable
          $url = $stats->getEffectiveUri();
        },
        'query' => $query,
      ]);
    }

    if ($response->getStatusCode() !== 200) {
      throw new \RuntimeException("Request to $url failed, returned {$response->getStatusCode()} with reason: {$response->getReasonPhrase()}");
    }

    return Json::decode($response->getBody()->getContents());
  }

  /**
   * Requests a node from the Drupal.org API.
   *
   * @param string $project
   *   The Drupal.org project to get the releases from.
   *
   * @return array
   *   An array releases.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   *   Thrown if request is unsuccessful.
   */
  private function getProjectReleasesFromSource(string $project): array {
    if ($project === 'drupal/core') {
      $project = 'drupal';
    }
    else {
      $project = str_replace(['drupal/', 'acquia/'], '', $project);
    }
    $response = $this->requestProjectReleases($project);
    if (array_key_exists('releases', $response)) {
      // Only one release.
      if (array_key_exists('name', $response['releases']['release'])) {
        $response['releases'] = [$response['releases']['release']];
      }
      // Multiple releases.
      else {
        $response['releases'] = $response['releases']['release'];
      }
    }
    // No releases.
    else {
      $response['releases'] = [];
    }

    return $response;
  }

  /**
   * Requests a node from the Drupal.org API.
   *
   * @param string $project
   *   The Drupal.org project name.
   *
   * @return array
   *   The response object.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   *   Thrown if request is unsuccessful.
   *
   * @see https://www.drupal.org/drupalorg/docs/apis/rest-and-other-apis#s-releases
   *
   * @see https://www.drupal.org/drupalorg/docs/apis/update-status-xml
   */
  protected function requestProjectReleases(string $project): array {
    $url = "https://updates.drupal.org/release-history/$project/current";
    $response = \Drupal::httpClient()->request('GET', $url);
    if ($response->getStatusCode() !== 200) {
      throw new \RuntimeException("Request to $url failed, returned {$response->getStatusCode()} with reason: {$response->getReasonPhrase()}");
    }
    $body = $response->getBody()->getContents();
    if (strpos($body, 'No release history was found for the requested project') !== FALSE) {
      return [];
    }

    $xml = \simplexml_load_string($body);
    return Json::decode(Json::encode($xml));
  }

  /**
   * Batch operation for creating fixtures.
   *
   * @param array $sandbox
   *   The batch sandbox.
   *
   * @return string
   *   Progress messages.
   */
  public function hackyFixtureMaker(&$sandbox): string {
    // Initialize some sandbox values on first iteration.
    if (!isset($sandbox['progress'])) {
      // The count of nodes visited so far.
      $sandbox['progress'] = 0;
      // Total nodes that must be visited.
      $sandbox['max'] = 7500;
      // A place to store messages during the run.
      $sandbox['messages'] = [];
      // Last node read via the query.
      $sandbox['current_page'] = 0;

      $sandbox['projects'] = [];
    }

    $query = [
      'page' => $sandbox['current_page'],
      'field_project_type' => 'full',
      'limit' => 50,
      'field_project_has_releases' => 1,
      'field_project_has_issue_queue' => 1,
      'type' => 'project_module',
      'status' => 1,
      'sort' => 'changed',
      'direction' => 'DESC',
    ];
    $earliest_possible_timestamp_reached = NULL;
    $drupal_org_response = $this->getProjectsFromSource($query);
    $returned_projects = $drupal_org_response['list'];

    if ($returned_projects) {
      foreach ($returned_projects as &$project) {
        $project['project_usage_total'] = 0;
        if (array_key_exists('project_usage', $project)) {
          foreach ($project['project_usage'] as $usage) {
            $project['project_usage_total'] += $usage;
          }
        }

        if (empty($project['body']['value'])) {
          $project['body']['value'] = '';
        }
        if (empty($project['body']['summary'])) {
          $project['body']['summary'] = $project['body']['value'];
        }
        $project['body']['summary'] = Xss::filter(strip_tags($project['body']['summary']));
        $project['body']['summary'] = Unicode::truncate($project['body']['summary'], 200, TRUE, TRUE);

        // Once we hit projects that haven't been updated since March 13, 2020, we
        // know they aren't compatible because it is before
        // https://www.drupal.org/node/3119415.
        if ($project['changed'] < 1583985600) {
          $earliest_possible_timestamp_reached = TRUE;
        }
      }
      $sandbox['current_page'] += 1;

      $projects_to_store = (array) $returned_projects;

      // Rewrite the projects array so each project has added release data and
      // unnecessary values are removed to conserve space.
      $projects_to_store = array_map(function ($a_project) {
        $the_project = (array) $a_project;
        $releases = $this->getProjectReleasesFromSource($the_project['field_project_machine_name']);
        if (!empty($releases['releases'])) {
          $compatible_releases = array_filter($releases['releases'], function ($release) {
            if (!empty($release['core_compatibility'])) {
              try {
                // Apparently there are multiple projects that have invalid
                // version strings.
                return Semver::satisfies(\Drupal::VERSION, $release['core_compatibility']);
              }
              catch (\Exception $exception) {
                // Don't include releases with invalid compatibility strings.
                return FALSE;
              }
            }
            return FALSE;
          });
          if (empty($compatible_releases)) {
            // Don't include projects without any compatible releases.
            return NULL;
          }
        }
        else {
          // Don't include projects without releases.
          return NULL;
        }

        // To keep filesize down, remove unnecessary items from release data.
        $the_project['releases'] = array_map(function ($release) {
          return [
            'version' => $release['version'],
            'status' => $release['status'],
            'date' => $release['date'] ?? NULL,
            'core_compatibility' => $release['core_compatibility'],
          ];
        }, $compatible_releases);
        $this->truncateProjectData($the_project);
        return $the_project;
      }, $projects_to_store);

      $projects_to_store = array_filter($projects_to_store);
      $sandbox['projects'] = array_merge($sandbox['projects'], $projects_to_store);
      $sandbox['progress'] += count($sandbox['projects']);
      $sandbox['#finished'] = count($sandbox['projects']) >= $sandbox['max'] || $earliest_possible_timestamp_reached ? TRUE : (count($sandbox['projects']) / $sandbox['max']);
    }
    else {
      $sandbox['#finished'] = TRUE;
    }

    if ($sandbox['#finished'] === TRUE) {
      $module_path = \Drupal::service('module_handler')->getModule('project_browser')->getPath();
      file_put_contents($module_path . '/fixtures/project_data.json', '[]');
      file_put_contents($module_path . '/fixtures/categories.json', '[]');

      $projects = $sandbox['projects'];

      $category_values = [];

      // Map fixture values to DB columns.
      $values = array_map(function ($project) use (&$category_values) {
        if (!empty($project['taxonomy_vocabulary_3'])) {
          foreach ($project['taxonomy_vocabulary_3'] as $category) {
            $category_values[] = [
              'tid' => $category['id'],
              'pid' => $project['nid'],
            ];
          }
        }

        return [
          'nid' => $project['nid'],
          'title' => $project['title'],
          'author' => (string) @$project['author']['name'],
          'created' => $project['created'],
          'changed' => $project['changed'],
          'project_usage_total' => $project['project_usage_total'] ?? 0,
          'taxonomy_vocabulary_44' => $project['taxonomy_vocabulary_44']['id'],
          'taxonomy_vocabulary_46' => $project['taxonomy_vocabulary_46']['id'],
          'status' => $project['status'],
          'field_security_advisory_coverage' => $project['field_security_advisory_coverage'],
          'flag_project_star_user_count' => $project['flag_project_star_user_count'] ?? 0,
          'field_project_type' => $project['field_project_type'] ?? '',
          'project_data' => serialize($project),
        ];
      }, $projects);

      $used_nids = [];
      $temp_array = Json::decode(file_get_contents($module_path . '/fixtures/project_data.json'));
      foreach ($values as $record) {
        if (in_array($record['nid'], $used_nids)) {
          continue;
        }
        $used_nids[] = $record['nid'];
        array_push($temp_array, (object) $record);
      }

      $all_records = Json::encode($temp_array);
      file_put_contents($module_path . '/fixtures/project_data.json', $all_records);

      $used_primary = [];
      $temp_array = Json::decode(file_get_contents($module_path . '/fixtures/categories.json'));

      $all_categories = [];
      foreach ($category_values as $record) {
        if (in_array($record['tid'] . $record['pid'], $used_primary)) {
          continue;
        }
        $used_primary[] = $record['tid'] . $record['pid'];
        array_push($temp_array, (object) $record);
        $all_categories = Json::encode($temp_array);
      }
      file_put_contents($module_path . '/fixtures/categories.json', $all_categories);

      return 'Fixture generation complete';
    }
    else {
      $last = $sandbox['projects'][array_key_last($sandbox['projects'])];
      return 'Page: ' . $sandbox['current_page'] . ' | Projects added:' . count($sandbox['projects']) . ' | ' . $last['changed'] . ' | ' . $sandbox['#finished'] * 100 . '%';
    }
  }

}
