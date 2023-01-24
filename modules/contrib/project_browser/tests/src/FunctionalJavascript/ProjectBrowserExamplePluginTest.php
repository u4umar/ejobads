<?php

namespace Drupal\Tests\project_browser\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Provides tests for the Project Browser Example plugins.
 *
 * @group project_browser
 */
class ProjectBrowserExamplePluginTest extends WebDriverTestBase {

  use ProjectBrowserUiTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'project_browser',
    'project_browser_source_example',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->drupalLogin($this->drupalCreateUser([
      'administer modules',
      'administer site configuration',
    ]));
    // Update configuration, enable only example source.
    $this->config('project_browser.admin_settings')->set('enabled_sources', ['project_browser_source_example'])->save(TRUE);
  }

  /**
   * Tests the Example plugin.
   */
  public function testExamplePlugin(): void {
    $assert_session = $this->assertSession();

    $this->getSession()->resizeWindow(1200, 1200);
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('css', '#project-browser .grid');
    $this->assertEquals('Grid', $this->getElementText('#project-browser .toggle-buttons .grid-button'));
    $assert_session->waitForElementVisible('css', '#project-browser .project');
    $this->assertTrue($assert_session->waitForText('Project 1'));
    $assert_session->pageTextNotContains('No records available');
    $this->svelteInitHelper('css', '.pb-categories input[type="checkbox"]');
    $assert_session->elementsCount('css', '.pb-categories input[type="checkbox"]', 2);
  }

}
