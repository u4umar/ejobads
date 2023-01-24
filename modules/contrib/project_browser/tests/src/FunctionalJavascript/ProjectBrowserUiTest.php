<?php

namespace Drupal\Tests\project_browser\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Provides tests for the Project Browser UI.
 *
 * These tests rely on a module that replaces Project Browser data with
 * test data.
 *
 * @see project_browser_test_install()
 *
 * @group project_browser
 */
class ProjectBrowserUiTest extends WebDriverTestBase {

  use ProjectBrowserUiTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'project_browser',
    'project_browser_test',
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
    $this->config('project_browser.admin_settings')->set('enabled_sources', ['drupalorg_mockapi'])->save(TRUE);
    $this->drupalLogin($this->drupalCreateUser([
      'administer modules',
      'administer site configuration',
    ]));
  }

  /**
   * Asserts that a given list of project titles are visible on the page.
   *
   * @param array $project_titles
   *   An array of expected titles.
   * @param bool $reload
   *   When TRUE, reload the page if the assertion fails and try again.
   *   This should typically be kept to the default value of FALSE. It only
   *   needs to be set to TRUE for calls that intermittently fail on DrupalCI.
   */
  protected function assertProjectsVisible(array $project_titles, bool $reload = FALSE): void {
    $count = count($project_titles);

    // Create a JavaScript string that checks the titles of the visible
    // projects. This is done with JavaScript to avoid issues with PHP
    // referencing an element that was rerendered and thus unavailable.
    $script = "document.querySelectorAll('#project-browser .project h3 a').length === $count";
    foreach ($project_titles as $key => $value) {
      $script .= " && document.querySelectorAll('#project-browser .project h3 a')[$key].textContent === '$value'";
    }

    // It can take a while for all items to render. Wait for the condition to be
    // true before asserting it.
    $this->getSession()->wait(10000, $script);

    if ($reload) {
      try {
        $this->assertTrue($this->getSession()->evaluateScript($script), 'Ran:' . $script . 'Svelte did not initialize. Markup: ' . $this->getSession()->evaluateScript('document.querySelector("#project-browser").innerHTML'));
      }
      catch (\Exception $e) {
        $this->getSession()->reload();
        $this->getSession()->wait(10000, $script);
      }
    }

    $this->assertTrue($this->getSession()->evaluateScript($script), 'Ran:' . $script . 'Svelte did not initialize. Markup: ' . $this->getSession()->evaluateScript('document.querySelector("#project-browser").innerHTML'));
  }

  /**
   * Asserts that a given list of pager items are present on the page.
   *
   * @param array $pager_items
   *   An array of expected pager item labels.
   */
  protected function assertPagerItems(array $pager_items): void {
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();

    $assert_session->waitForElementVisible('css', '#project-browser .project');
    $items = array_map(function ($element) {
      return $element->getText();
    }, $page->findAll('css', '#project-browser .pager__item'));
    $this->assertSame($pager_items, $items);
  }

  /**
   * Tests the grid view.
   */
  public function testGrid(): void {
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    $this->getSession()->resizeWindow(1250, 1000);
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('css', '.project.grid');
    $assert_session->waitForElementVisible('css', '#project-browser .toggle-buttons .grid-button');
    $grid_text = $this->getElementText('#project-browser .toggle-buttons .grid-button');
    $this->assertEquals('Grid', $grid_text);
    $assert_session->elementsCount('css', '#project-browser .project.grid', 9);
    $this->assertTrue($assert_session->waitForText('Results'));
    $assert_session->pageTextNotContains('No records available');
    $page->pressButton('List');
    $this->assertNotNull($assert_session->waitForElementVisible('css', '#project-browser .project.list'));
    $assert_session->elementsCount('css', '#project-browser .project.list', 9);
    $this->getSession()->resizeWindow(1100, 1000);
    $assert_session->assertNoElementAfterWait('css', '.toggle.list-button');
    $this->assertNotNull($assert_session->waitForElementVisible('css', '#project-browser .project.grid'));
    $assert_session->elementsCount('css', '#project-browser .project.grid', 9);
    $this->getSession()->resizeWindow(1210, 1210);
    $this->assertNotNull($assert_session->waitForElementVisible('css', '#project-browser .project.list'));
    $assert_session->elementsCount('css', '#project-browser .project.list', 9);
  }

  /**
   * Tests the available categories.
   */
  public function testCategories(): void {
    $assert_session = $this->assertSession();

    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('css', '.pb-categories input[type="checkbox"]');
    $assert_session->elementsCount('css', '.pb-categories input[type="checkbox"]', 54);
  }

  /**
   * Tests the clickable category functionality.
   */
  public function testClickableCategory(): void {
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Dancing Queen');
    $page->clickLink('Dancing Queen');
    $this->svelteInitHelper('text', 'E-commerce');

    // Click 'E-commerce' category on module page.
    $this->clickWithWait('li.category:nth-child(2)');
    $module_category_e_commerce_filter_selector = 'p.filters-applied:nth-child(4)';
    $this->assertEquals('E-commerce', $this->getElementText("$module_category_e_commerce_filter_selector .filter-label"));
    $this->assertTrue($assert_session->waitForText('6 Results'));
  }

  /**
   * Tests category filtering.
   */
  public function testCategoryFiltering(): void {
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();

    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('css', '#104');

    // Click 'E-commerce' checkbox.
    $this->clickWithWait('#104');

    $module_category_e_commerce_filter_selector = 'p.filters-applied:nth-child(4)';
    // Make sure the 'E-commerce' module category filter is applied.
    $this->assertEquals('E-commerce', $this->getElementText("$module_category_e_commerce_filter_selector .filter-label"));

    // This call has the second argument, `$reload`, set to TRUE due to it
    // failing on ~2% of DrupalCI test runs. It is not entirely clear why this
    // specific call intermittently fails while others do not. It's known the
    // Svelte app has occasional initialization problems on DrupalCI that are
    // reliably fixed by a page reload, so we allow that here to prevent random
    // failures that are not representative of real world use.
    $this->assertProjectsVisible([
      'Cream cheese on a bagel',
      'Dancing Queen',
      'Kangaroo',
      '9 Starts With a Higher Number',
      'Helvetica',
      'Astronaut Simulator',
    ], TRUE);

    $this->pressWithWait('Clear filters', '25 Results');

    // Click 'Media' checkbox.
    $this->clickWithWait('#67');

    // Click 'Commerce/Advertising' checkbox.
    $this->clickWithWait('#55');

    // Make sure the 'Media' module category filter is applied.
    $this->assertEquals('Media', $this->getElementText('p.filters-applied:nth-child(3) .filter-label'));
    // Assert that only media and administration module categories are shown.
    $this->assertProjectsVisible([
      'Jazz',
      'Eggman',
      'Tooth Fairy',
      'Vitamin&C;$?',
      'Cream cheese on a bagel',
      'Pinky and the Brain',
      'No Scrubs',
      'Soup',
      'Mad About You',
      'Dancing Queen',
      'Kangaroo',
      '9 Starts With a Higher Number',
    ]);
    $this->assertTrue($assert_session->waitForText('23 Results'));
  }

  /**
   * Tests the Target blank functionality.
   */
  public function testTargetBlank(): void {
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Helvetica');
    $page->clickLink('Helvetica');
    $this->assertTrue($assert_session->waitForText('Categories:'));
    $link = $page->find('css', '.box-2 a');
    $target = $link->getAttribute('target');
    $this->assertEquals('_blank', $target);

  }

  /**
   * Tests read-only input fields for referred commands.
   */
  public function testReadonlyFields(): void {
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Helvetica');
    $page->clickLink('Helvetica');
    $this->assertTrue($assert_session->waitForText('By Hel Vetica'));
    $this->clickWithWait('#project-browser .button--primary');
    $allowed_html_field = $assert_session->fieldExists('helvetica-download-command');
    $this->assertTrue($allowed_html_field->hasAttribute('readonly'));
    $allowed_html_field = $assert_session->fieldExists('helvetica-install-command');
    $this->assertTrue($allowed_html_field->hasAttribute('readonly'));
  }

  /**
   * Tests paging through results.
   */
  public function testPaging(): void {
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();

    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', '9 Results');

    $this->assertProjectsVisible([
      'Cream cheese on a bagel',
      'Pinky and the Brain',
      'Dancing Queen',
      'Kangaroo',
      '9 Starts With a Higher Number',
      'Octopus',
      'Helvetica',
      'Unwritten&:/',
      'Astronaut Simulator',
    ]);
    $this->assertPagerItems([]);

    $page->pressButton('Clear filters');
    $this->assertTrue($assert_session->waitForText('25 Results'));
    $this->assertProjectsVisible([
      'Jazz',
      'Eggman',
      'Tooth Fairy',
      'Vitamin&C;$?',
      'Cream cheese on a bagel',
      'Pinky and the Brain',
      'Ice Ice',
      'No Scrubs',
      'Soup',
      'Mad About You',
      'Dancing Queen',
      'Kangaroo',
    ]);
    $this->assertPagerItems(['1', '2', '3', 'Next', 'Last']);
    $assert_session->elementExists('css', '.pager__item--active > .is-active[aria-label="Page 1"]');

    $this->clickWithWait('[aria-label="Next page"]');
    $this->assertProjectsVisible([
      '9 Starts With a Higher Number',
      'Quiznos',
      'Octopus',
      'Helvetica',
      '1 Starts With a Number',
      'Ruh roh',
      'Fire',
      'Looper',
      'Grapefruit',
      'Become a Banana',
      'Unwritten&:/',
      'Doomer',
    ]);
    $this->assertPagerItems(['First', 'Previous', '1', '2', '3', 'Next', 'Last']);

    $this->clickWithWait('[aria-label="Next page"]');
    $this->assertProjectsVisible([
      'Astronaut Simulator',
    ]);
    $this->assertPagerItems(['First', 'Previous', '1', '2', '3']);

    // Ensure that when the number of projects is even divisible by the number
    // shown on a page, the pager has the correct number of items.
    $this->clickWithWait('[aria-label="First page"]');

    // Click 'Media' checkbox.
    $this->clickWithWait('#67', '', TRUE);

    // Click 'Commerce/Advertising' checkbox.
    $this->clickWithWait('#55', '', TRUE);

    // Click 'E-commerce' checkbox.
    $this->clickWithWait('#104', '24 Results');
    $this->assertPagerItems(['1', '2', 'Next', 'Last']);

    $this->clickWithWait('[aria-label="Next page"]');

    $this->assertPagerItems(['First', 'Previous', '1', '2']);
  }

  /**
   * Tests advanced filtering.
   */
  public function testAdvancedFiltering(): void {
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();

    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Astronaut Simulator');
    $this->pressWithWait('Clear filters');
    $this->pressWithWait('Recommended filters');

    $this->assertProjectsVisible([
      'Cream cheese on a bagel',
      'Pinky and the Brain',
      'Dancing Queen',
      'Kangaroo',
      '9 Starts With a Higher Number',
      'Octopus',
      'Helvetica',
      'Unwritten&:/',
      'Astronaut Simulator',
    ]);

    $second_filter_selector = 'p.filters-applied:nth-child(3)';
    // Make sure the second filter applied is the security covered filter.
    $this->assertEquals('Covered by a security policy', $this->getElementText("$second_filter_selector .filter-label"));

    // Clear the security covered filter.
    $this->clickWithWait("$second_filter_selector > button");
    $this->assertProjectsVisible([
      'Jazz',
      'Vitamin&C;$?',
      'Cream cheese on a bagel',
      'Pinky and the Brain',
      'Ice Ice',
      'No Scrubs',
      'Dancing Queen',
      'Kangaroo',
      '9 Starts With a Higher Number',
      'Quiznos',
      'Octopus',
      'Helvetica',
    ]);

    $this->openAdvancedFilter();

    // Click the Active filter.
    $assert_session->waitForElementVisible('css', '#developmentStatusactive');
    $this->clickWithWait('#developmentStatusactive');

    // Make sure the correct filter was applied.
    $this->assertEquals('Active', $this->getElementText('p.filters-applied:nth-child(2) .filter-label'));

    $this->assertProjectsVisible([
      'Jazz',
      'Cream cheese on a bagel',
      'Ice Ice',
      'No Scrubs',
      'Dancing Queen',
      'Kangaroo',
      '9 Starts With a Higher Number',
      'Octopus',
      'Helvetica',
      '1 Starts With a Number',
      'Become a Banana',
      'Astronaut Simulator',
    ]);

    // Click the "Show all" filter for security.
    $this->clickWithWait('#securityCoverageall', '', TRUE);
    $this->assertProjectsVisible([
      'Jazz',
      'Cream cheese on a bagel',
      'Ice Ice',
      'No Scrubs',
      'Dancing Queen',
      'Kangaroo',
      '9 Starts With a Higher Number',
      'Octopus',
      'Helvetica',
      '1 Starts With a Number',
      'Become a Banana',
      'Astronaut Simulator',
    ]);

    // Clear all filters.
    $this->pressWithWait('Clear filters', '25 Results');

    // Click the Actively maintained filter.
    $this->clickWithWait('#maintenanceStatusmaintained');
    $this->assertEquals('Maintained', $this->getElementText('p.filters-applied:nth-child(2) .filter-label'));

    $this->assertProjectsVisible([
      'Jazz',
      'Vitamin&C;$?',
      'Cream cheese on a bagel',
      'Pinky and the Brain',
      'Ice Ice',
      'No Scrubs',
      'Dancing Queen',
      'Kangaroo',
      '9 Starts With a Higher Number',
      'Quiznos',
      'Octopus',
      'Helvetica',
    ]);
  }

  /**
   * Tests sorting criteria.
   */
  public function testSortingCriteria(): void {
    $assert_session = $this->assertSession();
    // Clear filters.
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Clear Filters');
    $this->pressWithWait('Clear filters');
    $assert_session->elementsCount('css', '#pb-sort option', 4);
    $this->assertEquals('Active installs', $this->getElementText('#pb-sort option:nth-child(1)'));
    $this->assertEquals('A-Z', $this->getElementText('#pb-sort option:nth-child(2)'));
    $this->assertEquals('Z-A', $this->getElementText('#pb-sort option:nth-child(3)'));
    $this->assertEquals('Recently created', $this->getElementText('#pb-sort option:nth-child(4)'));

    // Select 'A-Z' sorting order.
    $this->sortBy('a_z');

    // Assert that the projects are listed in ascending order of their titles.
    $this->assertProjectsVisible([
      '1 Starts With a Number',
      '9 Starts With a Higher Number',
      'Astronaut Simulator',
      'Become a Banana',
      'Cream cheese on a bagel',
      'Dancing Queen',
      'Doomer',
      'Eggman',
      'Fire',
      'Grapefruit',
      'Helvetica',
      'Ice Ice',
    ]);

    // Select 'Z-A' sorting order.
    $this->sortBy('z_a');

    $this->assertProjectsVisible([
      'Vitamin&C;$?',
      'Unwritten&:/',
      'Tooth Fairy',
      'Soup',
      'Ruh roh',
      'Quiznos',
      'Pinky and the Brain',
      'Octopus',
      'No Scrubs',
      'Mad About You',
      'Looper',
      'Kangaroo',
    ]);

    // Select 'Active installs' option.
    $this->sortBy('usage_total');

    // Assert that the projects are listed in descending order of their usage.
    $this->assertProjectsVisible([
      'Jazz',
      'Eggman',
      'Tooth Fairy',
      'Vitamin&C;$?',
      'Cream cheese on a bagel',
      'Pinky and the Brain',
      'Ice Ice',
      'No Scrubs',
      'Soup',
      'Mad About You',
      'Dancing Queen',
      'Kangaroo',
    ]);

    // Select 'Recently created' option.
    $this->sortBy('created');

    // Assert that the projects are listed in descending order of their date of
    // creation.
    $this->assertProjectsVisible([
      '9 Starts With a Higher Number',
      'Helvetica',
      'Become a Banana',
      'Ice Ice',
      'Astronaut Simulator',
      'Grapefruit',
      'Fire',
      'Cream cheese on a bagel',
      'No Scrubs',
      'Soup',
      'Octopus',
      'Tooth Fairy',
    ]);
  }

  /**
   * Tests search with strings that need URI encoding.
   */
  public function testSearchForSpecialChar(): void {

    // Clear filters.
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', '9 Results');
    $this->pressWithWait('Clear filters', '25 Results');

    // Tests for the presence of search bar placeholder text.
    $search_field = $this->getSession()->getPage()->find('css', '#pb-text');
    $this->assertSame('Module Name, Keyword(s), etc.', $search_field->getAttribute('placeholder'));

    // Fill in the search field.
    $this->inputSearchField('', TRUE);
    $this->inputSearchField('&');
    $this->assertProjectsVisible([
      'Vitamin&C;$?',
      'Unwritten&:/',
    ]);

    // Fill in the search field.
    $this->inputSearchField('', TRUE);
    $this->inputSearchField('n&');
    $this->assertProjectsVisible([
      'Vitamin&C;$?',
      'Unwritten&:/',
    ]);

    $this->inputSearchField('', TRUE);
    $this->inputSearchField('$');
    $this->assertProjectsVisible([
      'Vitamin&C;$?',
    ]);

    $this->inputSearchField('', TRUE);
    $this->inputSearchField('?');
    $this->assertProjectsVisible([
      'Vitamin&C;$?',
    ]);

    $this->inputSearchField('', TRUE);
    $this->inputSearchField('/');
    $this->assertProjectsVisible([
      'Unwritten&:/',
    ]);

    $this->inputSearchField('', TRUE);
    $this->inputSearchField(':');
    $this->assertProjectsVisible([
      'Unwritten&:/',
    ]);

    $this->inputSearchField('', TRUE);
    $this->inputSearchField(';');
    $this->assertProjectsVisible([
      'Vitamin&C;$?',
    ]);
  }

  /**
   * Tests the detail page.
   */
  public function testDetailPage(): void {
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Helvetica');
    $page->clickLink('Helvetica');
    $this->assertTrue($assert_session->waitForText('By Hel Vetica'));
  }

  /**
   * Tests that filtering, sorting, paging persists.
   */
  public function testPersistence(): void {
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Clear Filters');
    $this->pressWithWait('Clear filters');

    $this->openAdvancedFilter();

    // Select 'Z-A' sorting order.
    $this->sortBy('z_a');

    // Select the active development status filter.
    $assert_session->waitForElementVisible('css', '#developmentStatusactive');
    $this->clickWithWait('#developmentStatusactive');

    // Select the Commerce/Advertising filter.
    $this->clickWithWait('#55', '', TRUE);

    // Select the Media filter.
    $this->clickWithWait('#67', '', TRUE);

    $this->assertTrue($assert_session->waitForText('16 Results'));
    $this->assertProjectsVisible([
      'Octopus',
      'No Scrubs',
      'Mad About You',
      'Looper',
      'Kangaroo',
      'Jazz',
      'Grapefruit',
      'Fire',
      'Eggman',
      'Doomer',
      'Dancing Queen',
      'Cream cheese on a bagel',
    ]);

    $this->clickWithWait('[aria-label="Next page"]');
    $this->assertProjectsVisible([
      'Become a Banana',
      'Astronaut Simulator',
      '9 Starts With a Higher Number',
      '1 Starts With a Number',
    ]);
    $this->getSession()->reload();
    // Should still be on second results page.
    $this->assertProjectsVisible([
      'Become a Banana',
      'Astronaut Simulator',
      '9 Starts With a Higher Number',
      '1 Starts With a Number',
    ]);
    $this->assertTrue($assert_session->waitForText('16 Results'));

    $this->assertEquals('Active', $this->getElementText('p.filters-applied:nth-child(2) .filter-label'));
    $this->assertEquals('Commerce/Advertising', $this->getElementText('p.filters-applied:nth-child(3) .filter-label'));
    $this->assertEquals('Media', $this->getElementText('p.filters-applied:nth-child(4) .filter-label'));

    $this->clickWithWait('[aria-label="First page"]');
    $this->assertProjectsVisible([
      'Octopus',
      'No Scrubs',
      'Mad About You',
      'Looper',
      'Kangaroo',
      'Jazz',
      'Grapefruit',
      'Fire',
      'Eggman',
      'Doomer',
      'Dancing Queen',
      'Cream cheese on a bagel',
    ]);

    $this->assertEquals('Active', $this->getElementText('p.filters-applied:nth-child(2) .filter-label'));
    $this->assertEquals('Commerce/Advertising', $this->getElementText('p.filters-applied:nth-child(3) .filter-label'));
    $this->assertEquals('Media', $this->getElementText('p.filters-applied:nth-child(4) .filter-label'));
  }

  /**
   * Tests recommended filters.
   */
  public function testRecommendedFilter(): void {
    $assert_session = $this->assertSession();
    // Clear filters.
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Clear Filters');
    $this->pressWithWait('Clear filters', '25 Results');
    $this->pressWithWait('Recommended filters');

    // Check that the actively maintained tag is present.
    $this->assertEquals('Maintained', $this->getElementText('p.filters-applied:nth-child(2) .filter-label'));
    // Make sure the second filter applied is the security covered filter.
    $this->assertEquals('Covered by a security policy', $this->getElementText('p.filters-applied:nth-child(3) .filter-label'));
    $this->assertTrue($assert_session->waitForText('9 Results'));
  }

  /**
   * Tests multiple source plugins at once.
   */
  public function testMultiplePlugins(): void {
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();
    // Enable module for extra source plugin.
    $this->container->get('module_installer')->install(['project_browser_devel'], TRUE);
    // Test categories with multiple plugin enabled.
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('css', '.pb-categories input[type="checkbox"]');
    $assert_session->elementsCount('css', '.pb-categories input[type="checkbox"]', 54);

    $this->svelteInitHelper('css', '#project-browser .project');
    // Count tabs.
    $tab_count = $page->findAll('css', '.plugin-tabs button');
    $this->assertCount(2, $tab_count);
    // Get result count for first tab.
    $this->assertEquals('9 Results Sorted by Active installs', $this->getElementText('.grid--1 output'));

    // Apply filters in drupalorg_mockapi(first tab).
    $assert_session->waitForElement('css', '.views-exposed-form__item input[type="checkbox"]');

    $this->pressWithWait('Clear filters', '25 Results');

    // Click 'E-commerce' checkbox.
    $this->clickWithWait('#104');

    // Click 'Media' checkbox.
    $this->clickWithWait('#67', '20 Results');

    // Filter by search text.
    $this->inputSearchField('th');
    $this->assertTrue($assert_session->waitForText('4 Results'));
    $this->assertProjectsVisible([
      'Tooth Fairy',
      'Pinky and the Brain',
      '9 Starts With a Higher Number',
      '1 Starts With a Number',
    ]);

    // Click other tab.
    $this->pressWithWait('random_data');
    $this->svelteInitHelper('css', '.pb-categories input[type="checkbox"]');
    $assert_session->elementsCount('css', '.pb-categories input[type="checkbox"]', 20);
    $assert_session->waitForElementVisible('css', '#project-browser .project');
    $this->assertNotEquals('9 Results Sorted by Active installs', $this->getElementText('.grid--1 output'));
    $assert_session->waitForElementVisible('css', '#project-browser .project');
    $result_count_text = $page->find('css', '.grid--1 output')->getText();
    $this->assertNotEquals('9 Results Sorted by Active installs', $result_count_text);
    // Apply the second module category filter.
    $second_category_filter_selector = '#project-browser > div.container-1 > div.box-1 > div > form > details > fieldset > label:nth-child(3)';
    $this->clickWithWait("$second_category_filter_selector");

    // Save the filter applied in second tab.
    $applied_filter = $this->getElementText('p.filters-applied:nth-child(2) .filter-label');
    // Save the number of results.
    $results_before = count($page->findAll('css', '#project-browser .project.list'));

    // Switch back to first tab.
    $page->pressButton('drupalorg_mockapi');
    // Assert that the filters persist.
    $this->assertTrue($assert_session->waitForText('4 Results'));
    $first_filter_element = $page->find('css', 'p.filters-applied:nth-child(2)');
    $this->assertEquals('E-commerce', $first_filter_element->find('css', '.filter-label')->getText());
    $second_filter_element = $page->find('css', 'p.filters-applied:nth-child(3)');
    $this->assertEquals('Media', $second_filter_element->find('css', '.filter-label')->getText());
    $this->assertProjectsVisible([
      'Tooth Fairy',
      'Pinky and the Brain',
      '9 Starts With a Higher Number',
      '1 Starts With a Number',
    ]);

    // Again switch to second tab.
    $page->pressButton('random_data');
    // Assert that the filters persist.
    $this->assertEquals($applied_filter, $this->getElementText('p.filters-applied:nth-child(2) .filter-label'));

    // Assert that the number of results is the same.
    $results_after = count($page->findAll('css', '#project-browser .project.list'));
    $this->assertEquals($results_before, $results_after);
  }

  /**
   * Tests the view mode toggle keeps its state.
   */
  public function testToggleViewState(): void {
    $page = $this->getSession()->getPage();
    $viewSwitches = [
      [
        'selector' => '.grid-button',
        'value' => 'Grid',
      ], [
        'selector' => '.list-button',
        'value' => 'List',
      ],
    ];
    $this->getSession()->resizeWindow(1300, 1300);

    foreach ($viewSwitches as $selector) {
      $this->drupalGet('admin/modules/browse');
      $this->svelteInitHelper('css', $selector['selector']);
      $this->getSession()->getPage()->pressButton($selector['value']);
      $this->svelteInitHelper('text', 'Helvetica');
      $page->clickLink('Helvetica');
      $this->svelteInitHelper('text', '‹ Browse');
      $page->clickLink('‹ Browse');
      $this->assertSession()->elementExists('css', $selector['selector'] . '.selected');

    }
  }

  /**
   * Tests tabledrag on configuration page.
   */
  public function testTabledrag(): void {
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();
    $this->container->get('module_installer')->install(['project_browser_devel'], TRUE);

    $this->drupalGet('admin/modules/browse');
    $assert_session->waitForElementVisible('css', '.plugin-tabs button');
    // Count tabs.
    $tab_count = $page->findAll('css', '.plugin-tabs button');
    $this->assertCount(2, $tab_count);

    // Verify that Drupal.org mockapi is first tab.
    $first_tab = $page->find('css', '.plugin-tabs button:nth-child(1)');
    $this->assertEquals('drupalorg_mockapi', $first_tab->getValue());

    // Re-order plugins.
    $this->drupalGet('admin/config/development/project_browser');
    $first_plugin = $page->find('css', '#source--drupalorg_mockapi');
    $second_plugin = $page->find('css', '#source--random_data');
    $first_plugin->find('css', '.handle')->dragTo($second_plugin);
    $assert_session->assertWaitOnAjaxRequest();
    $this->submitForm([], 'Save');

    // Verify that Random data is first tab.
    $this->drupalGet('admin/modules/browse');
    $assert_session->waitForElementVisible('css', '#project-browser .project');
    $first_tab = $page->find('css', '.plugin-tabs button:nth-child(1)');
    $this->assertEquals('random_data', $first_tab->getValue());

    // Disable Drupal.org mockapi plugin.
    $this->drupalGet('admin/config/development/project_browser');
    $enabled_row = $page->find('css', '#source--drupalorg_mockapi');
    $disabled_region_row = $page->find('css', '.status-title-disabled');
    $enabled_row->find('css', '.handle')->dragTo($disabled_region_row);
    $assert_session->assertWaitOnAjaxRequest();
    $this->submitForm([], 'Save');
    $assert_session->pageTextContains('The configuration options have been saved.');

    // Verify that only Random data plugin is enabled.
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('css', '.pb-categories input[type="checkbox"]');
    $assert_session->elementsCount('css', '.pb-categories input[type="checkbox"]', 20);

    // Enable only Drupal.org mockapi plugin through config update.
    // It is done this way because dragging was not working reliably for enabling Drupal.org mockapi plugin.
    $this->config('project_browser.admin_settings')->set('enabled_sources', ['drupalorg_mockapi'])->save(TRUE);
    $this->drupalGet('admin/config/development/project_browser');
    $this->assertTrue($assert_session->optionExists('edit-enabled-sources-drupalorg-mockapi-status', 'enabled')->isSelected());
    $this->assertTrue($assert_session->optionExists('edit-enabled-sources-random-data-status', 'disabled')->isSelected());

    // Verify that only Drupal.org mockapi plugin is enabled.
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('css', '.pb-categories input[type="checkbox"]');
    $assert_session->elementsCount('css', '.pb-categories input[type="checkbox"]', 54);
  }

  /**
   * Tests the visibility of categories in list and grid view.
   */
  public function testCategoriesVisibility(): void {
    $assert_session = $this->assertSession();
    $view_options = [
      [
        'selector' => '.grid-button',
        'value' => 'Grid',
      ], [
        'selector' => '.list-button',
        'value' => 'List',
      ],
    ];
    $this->getSession()->resizeWindow(1300, 1300);

    // Check visibility of categories in each view.
    foreach ($view_options as $selector) {
      $this->drupalGet('admin/modules/browse');
      $this->svelteInitHelper('css', $selector['selector']);
      $this->getSession()->getPage()->pressButton($selector['value']);
      $this->svelteInitHelper('text', 'Helvetica');
      $assert_session->elementsCount('css', '#project-browser .box-2 ul li:nth-child(7) .categories ul li', 1);
      $grid_text = $this->getElementText('#project-browser .box-2 ul li:nth-child(7) .categories ul li:nth-child(1)');
      $this->assertEquals('E-commerce', $grid_text);
      $assert_session->elementsCount('css', '#project-browser .box-2  ul li:nth-child(9) .categories ul li', 2);
      $grid_text = $this->getElementText('#project-browser .box-2 ul li:nth-child(9) .categories ul li:nth-child(1)');
      $this->assertEquals('Commerce/Advertising', $grid_text);
      $grid_text = $this->getElementText('#project-browser .box-2 ul li:nth-child(9) .categories ul li:nth-child(2)');
      $this->assertEquals('E-commerce', $grid_text);
    }
  }

  /**
   * Tests the pagination and filtering.
   */
  public function testPaginationWithFilters(): void {
    $assert_session = $this->assertSession();

    $this->drupalGet('admin/modules/browse');
    $this->pressWithWait('Clear filters');
    $this->assertProjectsVisible([
      'Jazz',
      'Eggman',
      'Tooth Fairy',
      'Vitamin&C;$?',
      'Cream cheese on a bagel',
      'Pinky and the Brain',
      'Ice Ice',
      'No Scrubs',
      'Soup',
      'Mad About You',
      'Dancing Queen',
      'Kangaroo',
    ]);

    $this->assertPagerItems(['1', '2', '3', 'Next', 'Last']);
    $this->clickWithWait('[aria-label="Last page"]');
    $this->assertProjectsVisible([
      'Astronaut Simulator',
    ]);

    // Click 'Media' checkbox.
    $this->clickWithWait('#67');
    $this->assertPagerItems(['1', '2', 'Next', 'Last']);
    $assert_session->elementExists('css', '.pager__item--active > .is-active[aria-label="Page 1"]');
  }

  /**
   * Tests install button link.
   */
  public function testInstallButtonLink(): void {
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();
    $this->config('project_browser.admin_settings')
      ->set('enabled_sources', ['drupal_core'])
      ->save(TRUE);
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('css', '.project.grid');

    $this->inputSearchField('inline form errors');
    $this->svelteInitHelper('text', 'Inline Form Errors');

    $install_link = $page->find('css', '.box-2 .action a');

    $this->assertStringContainsString('admin/modules#module-inline-form-errors', $install_link->getAttribute('href'));
    $this->drupalGet($install_link->getAttribute('href'));
    $assert_session->waitForElementVisible('css', "#edit-modules-inline-form-errors-enable");
    $assert_session->assertVisibleInViewport('css', '#edit-modules-inline-form-errors-enable');
  }

  /**
   * Confirms UI install can not be enabled without Package Manager installed.
   */
  public function testUiInstallNeedsPackageManager() {
    $this->drupalGet('admin/config/development/project_browser');
    $ui_install_input = $this->getSession()->getPage()->find('css', '[data-drupal-selector="edit-allow-ui-install"]');
    $this->assertTrue($ui_install_input->getAttribute('disabled') === 'disabled');
    $this->container->get('module_installer')->install(['package_manager'], TRUE);
    $this->drupalGet('admin/config/development/project_browser');
    $ui_install_input = $this->getSession()->getPage()->find('css', '[data-drupal-selector="edit-allow-ui-install"]');
    $this->assertFalse($ui_install_input->hasAttribute('disabled'));
  }

}
