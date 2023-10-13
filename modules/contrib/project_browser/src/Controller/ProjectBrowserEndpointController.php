<?php

namespace Drupal\project_browser\Controller;

// cspell:ignore tabwise

use Drupal\Component\Serialization\Json;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\project_browser\EnabledSourceHandler;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for the proxy layer.
 */
class ProjectBrowserEndpointController extends ControllerBase {

  public function __construct(
    private readonly EnabledSourceHandler $enabledSource,
    private readonly CacheBackendInterface $cacheBin,
  ) {
    $plugin_ids = [];
    $current_sources = $this->enabledSource->getCurrentSources();
    foreach ($current_sources as $source) {
      $plugin_ids[] = $source->getPluginId();
    }
    $cache_key = 'project_browser:enabled_source';
    $cached_enabled_source = $this->cacheBin->get($cache_key);
    if ($cached_enabled_source === FALSE || ($cached_enabled_source->data != $plugin_ids)) {
      $this->cacheBin->deleteAll();
      $this->cacheBin->set($cache_key, $plugin_ids);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('project_browser.enabled_source'),
      $container->get('cache.project_browser'),
    );
  }

  /**
   * Responds to GET requests.
   *
   * Returns a list of bundles for specified entity.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Typically a project listing.
   */
  public function getAllProjects(Request $request) {
    $current_sources = $this->enabledSource->getCurrentSources();
    if (!$current_sources) {
      return new JsonResponse([], Response::HTTP_ACCEPTED);
    }

    // Validate and build query.
    $query = [
      'page' => (int) $request->query->get('page', 0),
      'limit' => (int) $request->query->get('limit', 12),
    ];

    $machine_name = $request->query->get('machine_name');
    if ($machine_name) {
      $query['machine_name'] = $machine_name;
    }

    $sort = $request->query->get('sort');
    if ($sort) {
      $query['sort'] = $sort;
    }

    $title = $request->query->get('search');
    if ($title) {
      $query['search'] = $title;
    }

    $categories = $request->query->get('categories');
    if ($categories) {
      $query['categories'] = $categories;
    }

    $maintenance_status = $request->query->get('maintenance_status');
    if ($maintenance_status) {
      $query['maintenance_status'] = $maintenance_status;
    }

    $development_status = $request->query->get('development_status');
    if ($development_status) {
      $query['development_status'] = $development_status;
    }

    $security_advisory_coverage = $request->query->get('security_advisory_coverage');
    if ($security_advisory_coverage) {
      $query['security_advisory_coverage'] = $security_advisory_coverage;
    }

    $displayed_source = $request->query->get('source', 0);
    if ($displayed_source) {
      $query['source'] = $displayed_source;
    }
    // Done to cache results.
    $tabwise_categories = $request->query->get('tabwise_categories');
    if ($tabwise_categories) {
      $query['tabwise_categories'] = $tabwise_categories;
    }

    // Cache only exact query, down to the page number.
    $cache_key = 'project_browser:projects:' . md5(Json::encode($query));
    if ($projects = $this->cacheBin->get($cache_key)) {
      $projects = $projects->data;
    }
    else {
      $projects = [];
      $query_categories = $query['categories'] ?? '';
      unset($query['categories']);
      unset($query['tabwise_categories']);
      foreach ($current_sources as $source_name => $source) {
        $categories = [];
        // If the source is not the one currently displayed in the UI, request
        // page 0.
        $paging = !empty($displayed_source) && $displayed_source !== $source_name ? ['page' => 0] : [];
        // Get tab-wise results based on category filter.
        if (!empty($displayed_source) && $displayed_source !== $source_name) {
          if ($tabwise_categories) {
            $all_categories = Json::decode($tabwise_categories);
            $categories = (isset($all_categories[$source_name]) && !empty($all_categories[$source_name])) ? ['categories' => implode(", ", $all_categories[$source_name])] : [];
          }
        }
        else {
          $categories['categories'] = $query_categories;
        }
        $projects[$source_name] = $source->getProjects(array_merge($query, $paging, $categories));
      }
      $this->cacheBin->set($cache_key, $projects);
    }

    return new JsonResponse($projects);
  }

  /**
   * Returns a list of categories.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request.
   */
  public function getAllCategories(Request $request) {
    $current_sources = $this->enabledSource->getCurrentSources();
    if (!$current_sources) {
      return new JsonResponse([], Response::HTTP_ACCEPTED);
    }

    $cache_key = 'project_browser:categories';
    $categories = $this->cacheBin->get($cache_key) ?: [];
    if ($categories) {
      $categories = $categories->data;
    }
    else {
      foreach ($current_sources as $source) {
        $categories[$source->getPluginId()] = $source->getCategories();
      }
      $this->cacheBin->set($cache_key, $categories);
    }

    return new JsonResponse($categories);
  }

}
