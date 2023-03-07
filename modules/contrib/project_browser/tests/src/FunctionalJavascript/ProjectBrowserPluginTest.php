<?php

namespace Drupal\Tests\project_browser\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Provides tests for the Project Browser Plugins.
 *
 * @group project_browser
 */
class ProjectBrowserPluginTest extends WebDriverTestBase {

  use ProjectBrowserUiTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'project_browser',
    'project_browser_devel',
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
    // Update configuration, enable only random_data source.
    $this->config('project_browser.admin_settings')->set('enabled_sources', ['random_data'])->save(TRUE);
  }

  /**
   * Tests the Random Data plugin.
   */
  public function testRandomDataPlugin(): void {
    $assert_session = $this->assertSession();

    $this->getSession()->resizeWindow(1200, 1200);
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('css', '#project-browser .project--grid');
    $this->assertEquals('Grid', $this->getElementText('#project-browser .project-browser__toggle-buttons .project-browser__grid-button'));
    $assert_session->waitForElementVisible('css', '#project-browser .project');
    $this->assertTrue($assert_session->waitForText('Results'));
    $assert_session->pageTextNotContains('No modules found');
  }

  /**
   * Tests the available categories.
   */
  public function testCategories(): void {
    $assert_session = $this->assertSession();

    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('css', '.filter__checkbox');
    $assert_session->elementsCount('css', '.filter__checkbox', 20);
  }

  /**
   * Tests paging through results.
   *
   * We want to click through things and make sure that things are functional.
   * We don't care about the number of results.
   */
  public function testPaging(): void {
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();

    $this->drupalGet('admin/modules/browse');
    // Immediately clear filters so there are enough visible to enable paging.
    $this->svelteInitHelper('test', 'Clear Filters');
    $this->svelteInitHelper('css', '.pager__item--next');
    $assert_session->elementsCount('css', '.pager__item--next', 2);

    $page->find('css', 'a[aria-label="Next page"]')->click();
    $this->assertNotNull($assert_session->waitForElement('css', '.pager__item--previous'));
    $assert_session->elementsCount('css', '.pager__item--previous', 2);
  }

  /**
   * Tests advanced filtering.
   */
  public function testAdvancedFiltering(): void {
    $assert_session = $this->assertSession();

    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Results');

    // Make sure the second filter applied is the security covered filter.
    $this->assertEquals('Covered by a security policy', $this->getElementText('p.filter-applied:last-of-type .filter-applied__label'));

    $this->openAdvancedFilter();
    $security_radio_option_selector = '.filter-group:last-child input:checked ~ label';
    $maintenance_radio_option_selector = '.filter-group:nth-child(2) input:checked ~ label';
    $assert_session->waitForElementVisible('css', $security_radio_option_selector);
    $this->assertEquals(trim('Covered by a security policy'), $this->getElementText($security_radio_option_selector));
    $this->assertEquals('Maintained', $this->getElementText($maintenance_radio_option_selector));

    // Clear the security covered filter.
    $this->clickWithWait("p.filter-applied:last-of-type > button");
    $this->assertEquals('Show all', $this->getElementText($security_radio_option_selector));

    // Clear all filters.
    $this->pressWithWait('Clear filters');
    $this->assertEquals('Show all', $this->getElementText($security_radio_option_selector));
    $this->assertEquals('Show all', $this->getElementText($security_radio_option_selector));
  }

  /**
   * Tests broken images.
   */
  public function testBrokenImages(): void {
    $assert_session = $this->assertSession();

    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('css', 'img[src$="images/puzzle-piece-placeholder.svg"]');

    // RandomData always give an image URL. Sometimes it is a fake URL on
    // purpose so it 404s. This check means that the original image was not
    // found and it was replaced by the placeholder.
    $assert_session->elementExists('css', 'img[src$="images/puzzle-piece-placeholder.svg"]');
  }

  /**
   * Tests the not-compatible flag.
   */
  public function testNotCompatibleText(): void {
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('css', '.project_status-indicator');
    $this->assertEquals($this->getElementText('.project_status-indicator .visually-hidden') . ' Not compatible', $this->getElementText('.project_status-indicator'));
  }

  /**
   * Tests the detail page.
   */
  public function testDetailPageRandomDataPlugin(): void {
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    $this->drupalGet('admin/modules/browse');
    $assert_session->waitForElementVisible('css', '#project-browser .project');
    $this->assertTrue($assert_session->waitForText('Results'));

    $assert_session->waitForElementVisible('css', '.project .project__title');
    $first_project_selector = $page->find('css', '.project .project__title');
    $first_project_selector->click();
    $this->assertTrue($assert_session->waitForText('sites report using this module'));
  }

}
