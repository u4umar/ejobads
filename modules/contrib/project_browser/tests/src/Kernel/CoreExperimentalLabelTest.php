<?php

namespace Drupal\Tests\project_browser\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\project_browser\ProjectBrowser\Project;

/**
 * Tests 'Core (Experimental)' label change.
 *
 * @coversDefaultClass \Drupal\project_browser\Plugin\ProjectBrowserSource\DrupalCore
 *
 * @group project_browser
 */
class CoreExperimentalLabelTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'project_browser',
  ];

  /**
   * Tests 'Core (Experimental)' label.
   *
   * This test will fail if 'Workspaces' is removed, made non-experimental,
   * or if the language for experimental modules has changed.
   * If it is reason 1 or 2, the test may be fixed by changing 'Workspaces'
   * to another module that is currently experimental. If it's reason 3,
   * we need to update `DrupalCore::projectIsCovered` to look for the new
   * language that indicates a module is experimental.
   *
   * @covers ::getProjectData
   */
  public function testCoreExperimentalLabel(): void {
    /** @var \Drupal\project_browser\Plugin\ProjectBrowserSourceInterface $plugin_instance */
    $plugin_instance = $this->container->get('plugin.manager.project_browser.source')->createInstance('drupal_core');
    $modules_to_test = ['Workspaces', 'System'];
    $filtered_projects = array_filter($plugin_instance->getProjects()->list, fn(Project $value) => in_array($value->getTitle(), $modules_to_test));
    $this->assertCount(2, $filtered_projects);
    foreach ($filtered_projects as $project) {
      if ($project->getTitle() === 'System') {
        $this->assertTrue($project->isCovered());
      }
      elseif ($project->getTitle() === 'Workspaces') {
        $this->assertFalse($project->isCovered());
      }
    }
  }

}
