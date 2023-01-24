<?php

namespace Drupal\project_browser\ProjectBrowser;

/**
 * One page of search results from a query.
 */
class ProjectsResultsPage {
  /**
   * Total results that match the query.
   *
   * @var int
   */
  public $totalResults;

  /**
   * List of projects for one page of the query.
   *
   * @var \Drupal\project_browser\ProjectBrowser\Project[]
   *   An array of projects.
   */
  public $list = [];

  /**
   * Plugin label to display in front-end.
   *
   * @var string
   */
  public $pluginLabel;

  /**
   * Plugin ID to be used in API.
   *
   * @var string
   */
  public $pluginId;

  /**
   * Constructor.
   *
   * @param int $total_results
   *   The total results that match the query.
   * @param array $list
   *   The list of projects for one page.
   * @param string $plugin_label
   *   Plugin label to display in front-end.
   * @param string $plugin_id
   *   Plugin ID to be used in the API.
   */
  public function __construct(int $total_results, array $list, string $plugin_label, string $plugin_id) {
    $this->totalResults = $total_results;
    $this->list = $list;
    $this->pluginLabel = $plugin_label;
    $this->pluginId = $plugin_id;
  }

}
