<?php

namespace Drupal\Tests\project_browser\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Functional tests for Project Browser.
 *
 * @group project_browser
 */
class ProjectBrowserTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'project_browser',
    'system',
    'user',
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
    $this->drupalLogin($this->drupalCreateUser(['administer modules']));
  }

  /**
   * Tests 'Drupal.org mockapi plugin' status messages.
   */
  public function testMockPluginStatusMessage(): void {
    $assert = $this->assertSession();
    $this->drupalGet('admin/modules/browse');

    $assert->pageTextContains('Project Browser is currently a prototype, and the projects listed may not be up to date with Drupal.org. For the most updated list of projects, please visit https://www.drupal.org/project/project_module');
    $assert->elementAttributeContains('css', 'div[aria-label="Status message"] ul li:nth-of-type(1) a', 'href', 'https://www.drupal.org/project/project_module');
    $assert->pageTextContains('Your feedback and input are welcome at https://www.drupal.org/project/issues/project_browser');
    $assert->elementAttributeContains('css', 'div[aria-label="Status message"] ul li:nth-of-type(2) a', 'href', 'https://www.drupal.org/project/issues/project_browser');
    // Enable another plugin to check that this message is not present in this case.
    $this->config('project_browser.admin_settings')->set('enabled_sources', ['drupal_core'])->save(TRUE);
    $this->getSession()->reload();
    $assert->pageTextNotContains('Project Browser is currently a prototype, and the projects listed may not be up to date with Drupal.org. For the most updated list of projects, please visit https://www.drupal.org/project/project_module');
    $assert->pageTextNotContains('Your feedback and input are welcome at https://www.drupal.org/project/issues/project_browser');
  }

}
