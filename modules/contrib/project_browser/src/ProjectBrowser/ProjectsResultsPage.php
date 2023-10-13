<?php

namespace Drupal\project_browser\ProjectBrowser;

/**
 * One page of search results from a query.
 */
class ProjectsResultsPage {

  public function __construct(
    public int $totalResults,
    public array $list,
    public string $pluginLabel,
    public string $pluginId,
    public bool $isPackageManagerRequired,
  ) {}

}
