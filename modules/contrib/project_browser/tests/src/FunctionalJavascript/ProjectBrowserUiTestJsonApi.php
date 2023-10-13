<?php

namespace Drupal\Tests\project_browser\FunctionalJavascript;

// cspell:ignore Blazy Cardless Colorbox Сontact ctools Fivestar Flipbook Fontawesome Iconpicker IMCE Micon Plupload Statusactive Statusmaintained Tagify Webform Zotero Zurb ZURB

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * ProjectBrowserUITest refactored to use the Drupal.org JSON API endpoint.
 *
 * @group project_browser
 */
class ProjectBrowserUiTestJsonApi extends WebDriverTestBase {

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
    $this->config('project_browser.admin_settings')->set('enabled_sources', ['drupalorg_jsonapi'])->save(TRUE);
    $this->drupalLogin($this->drupalCreateUser([
      'administer modules',
      'administer site configuration',
    ]));
  }

  /**
   * Tests the grid view.
   */
  public function testGrid(): void {
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    $this->getSession()->resizeWindow(1250, 1000);
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('css', '.project.project--grid');
    $assert_session->waitForElementVisible('css', '#project-browser .project-browser__toggle-buttons .project-browser__grid-button');
    $grid_text = $this->getElementText('#project-browser .project-browser__toggle-buttons .project-browser__grid-button');
    $this->assertEquals('Grid', $grid_text);
    $this->assertTrue($assert_session->waitForText('Results'));
    $assert_session->pageTextNotContains('No records available');
    $page->pressButton('List');
    $this->assertNotNull($assert_session->waitForElementVisible('css', '#project-browser .project.project--list'));
    $assert_session->elementsCount('css', '#project-browser .project.project--list', 12);
    $this->getSession()->resizeWindow(1100, 1000);
    $assert_session->assertNoElementAfterWait('css', '.toggle.list-button');
    $this->assertNotNull($assert_session->waitForElementVisible('css', '#project-browser .project.project--grid'));
    $assert_session->elementsCount('css', '#project-browser .project.project--grid', 12);
    $this->getSession()->resizeWindow(1210, 1210);
    $this->assertNotNull($assert_session->waitForElementVisible('css', '#project-browser .project.project--list'));
    $assert_session->elementsCount('css', '#project-browser .project.project--list', 12);
  }

  /**
   * Tests the available categories.
   */
  public function testCategories(): void {
    $assert_session = $this->assertSession();

    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('css', '.filter__categories input[type="checkbox"]');
    $assert_session->elementsCount('css', '.filter__categories input[type="checkbox"]', 54);
  }

  /**
   * Tests the clickable category functionality.
   */
  public function testClickableCategory(): void {
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Token');
    $page->clickLink('Token');
    $this->svelteInitHelper('text', 'By dries_1');

    // Click 'Utility' category on module page.
    $this->clickWithWait('.module-page__project-data .module-page__category-list li:nth-child(2)');
    $module_category_utility_filter_selector = 'p.filter-applied:nth-child(3)';
    $this->assertEquals('Utility', $this->getElementText("$module_category_utility_filter_selector .filter-applied__label"));
    $this->assertTrue($assert_session->waitForText('732 Results'));
  }

  /**
   * Tests category filtering.
   */
  public function testCategoryFiltering(): void {
    $assert_session = $this->assertSession();

    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('css', '#acc38507-ac85-43e6-8f32-beb3febea93f');

    // Click 'E-commerce' checkbox.
    $this->clickWithWait('#acc38507-ac85-43e6-8f32-beb3febea93f');

    $module_category_e_commerce_filter_selector = 'p.filter-applied:nth-child(3)';
    // Make sure the 'E-commerce' module category filter is applied.
    $this->assertEquals('E-commerce', $this->getElementText("$module_category_e_commerce_filter_selector .filter-applied__label"));

    // This call has the second argument, `$reload`, set to TRUE due to it
    // failing on ~2% of DrupalCI test runs. It is not entirely clear why this
    // specific call intermittently fails while others do not. It's known the
    // Svelte app has occasional initialization problems on DrupalCI that are
    // reliably fixed by a page reload, so we allow that here to prevent random
    // failures that are not representative of real world use.
    $this->assertProjectsVisible([
      'Commerce Core', 'AddToAny Share Buttons', 'Fivestar', 'Commerce Shipping', 'Physical Fields', 'Commerce Stock', 'Entity Registration', 'Commerce Feeds', 'Currency', 'Commerce Migrate', 'Payment', 'Commerce Stripe',
    ], TRUE);

    $this->pressWithWait('Clear filters', '197 Results');

    // Click 'Media' checkbox.
    $this->clickWithWait('#ee0200ec-4920-411e-9768-2cc588deaa38');

    // Click 'Commerce/Advertising' checkbox.
    $this->clickWithWait('#23d470f6-ffde-4034-a6ef-492b7121b9cf');

    // Make sure the 'Media' module category filter is applied.
    $this->assertEquals('Media', $this->getElementText('p.filter-applied:nth-child(2) .filter-applied__label'));
    // Assert that only media and administration module categories are shown.
    $this->assertProjectsVisible([
      'IMCE', 'CKEditor - WYSIWYG HTML editor', 'Video Embed Field', 'Flex Slider', 'Entity Browser', 'Crop API', 'Embed', 'Entity Embed', 'Plupload integration', 'Slick Carousel', 'Focal Point', 'Blazy',
    ]);
    $this->assertTrue($assert_session->waitForText('828 Results'));
  }

  /**
   * Tests the Target blank functionality.
   */
  public function testTargetBlank(): void {
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Token');
    $page->clickLink('Token');
    $this->assertTrue($assert_session->waitForText('Categories'));
    $link = $page->find('css', '.module-page__project-description a');
    $target = $link->getAttribute('target');
    $this->assertEquals('_blank', $target);
  }

  /**
   * Tests paging through results.
   */
  public function testPaging(): void {
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();

    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', '4,523 Results');

    $this->assertProjectsVisible([
      'Chaos Tool Suite (ctools)', 'Token', 'Pathauto', 'Libraries API', 'Entity API', 'Webform', 'Metatag', 'Field Group', 'IMCE', 'CAPTCHA', 'Google Analytics', 'Redirect',
    ]);
    $this->assertPagerItems(['1', '2', '3', '4', '5', '…', 'Next', 'Last']);

    $page->pressButton('Clear filters');
    $this->assertTrue($assert_session->waitForText('7,236 Results'));
    $this->assertProjectsVisible([
      'Chaos Tool Suite (ctools)', 'Token', 'Pathauto', 'Libraries API', 'Entity API', 'Webform', 'Metatag', 'Field Group', 'IMCE', 'CAPTCHA', 'Google Analytics', 'Redirect',
    ]);
    $this->assertPagerItems(['1', '2', '3', '4', '5', '…', 'Next', 'Last']);
    $assert_session->elementExists('css', '.pager__item--active > .is-active[aria-label="Page 1"]');

    $this->clickWithWait('[aria-label="Next page"]');
    $this->assertProjectsVisible([
      'Module Filter', 'CKEditor - WYSIWYG HTML editor', 'Admin Toolbar', 'Views Bulk Operations (VBO)', 'Colorbox', 'XML sitemap', 'Features', 'Backup and Migrate', 'Devel', 'Rules', 'Paragraphs', 'Mail System',
    ]);
    $this->assertPagerItems(['First', 'Previous', '1', '2', '3', '4', '5', '6', '…', 'Next', 'Last']);

    $this->clickWithWait('[aria-label="Next page"]');
    $this->assertProjectsVisible([
      'Menu Block', 'Entity Reference Revisions', 'reCAPTCHA', 'Better Exposed Filters', 'File Entity (fieldable files)', 'Panels', 'Views Slideshow', 'Block Class', 'Inline Entity Form', 'Honeypot', 'SMTP Authentication Support', 'Search API',
    ]);
    $this->assertPagerItems(['First', 'Previous', '1', '2', '3', '4', '5', '6', '7', '…', 'Next', 'Last']);

    // Ensure that when the number of projects is even divisible by the number
    // shown on a page, the pager has the correct number of items.
    $this->clickWithWait('[aria-label="First page"]');

    // Click 'Commerce/Advertising' checkbox.
    $this->clickWithWait('#23d470f6-ffde-4034-a6ef-492b7121b9cf', '', TRUE);

    // Click 'E-commerce' checkbox.
    $this->clickWithWait('#acc38507-ac85-43e6-8f32-beb3febea93f', TRUE);

    // Click 'Utility' checkbox.
    $this->clickWithWait('#fddb4569-cb89-42f5-8699-182b10234dfa', '557 Results');
    $this->assertPagerItems(['1', '2', '3', '4', '5', '…', 'Next', 'Last']);
  }

  /**
   * Tests advanced filtering.
   */
  public function testAdvancedFiltering(): void {
    $assert_session = $this->assertSession();

    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Token');
    $this->pressWithWait('Clear filters');
    $this->pressWithWait('Recommended filters');
    $this->assertProjectsVisible([
      'Chaos Tool Suite (ctools)', 'Token', 'Pathauto', 'Libraries API', 'Entity API', 'Webform', 'Metatag', 'Field Group', 'IMCE', 'CAPTCHA', 'Google Analytics', 'Redirect',
    ]);

    $second_filter_selector = 'p.filter-applied:nth-child(2)';
    // Make sure the second filter applied is the security covered filter.
    $this->assertEquals('Covered by a security policy', $this->getElementText("$second_filter_selector .filter-applied__label"));

    // Clear the security covered filter.
    $this->clickWithWait("$second_filter_selector > button");
    $this->assertProjectsVisible([
      'Chaos Tool Suite (ctools)', 'Token', 'Pathauto', 'Libraries API', 'Entity API', 'Webform', 'Metatag', 'Field Group', 'IMCE', 'CAPTCHA', 'Google Analytics', 'Redirect',
    ]);

    $this->openAdvancedFilter();

    // Click the Active filter.
    $assert_session->waitForElementVisible('css', '#developmentStatusactive');
    $this->clickWithWait('#developmentStatusactive');

    // Make sure the correct filter was applied.
    $this->assertEquals('Active', $this->getElementText('p.filter-applied:nth-child(1) .filter-applied__label'));
    $assert_session->waitForText('No records available');

    // Clear all filters.
    $this->pressWithWait('Clear filters', 'Results');

    // Click the Actively maintained filter.
    $this->clickWithWait('#maintenanceStatusmaintained', '6,749 Results');
    $this->assertEquals('Maintained', $this->getElementText('p.filter-applied:nth-child(1) .filter-applied__label'));

    $this->assertProjectsVisible([
      'Chaos Tool Suite (ctools)', 'Token', 'Pathauto', 'Libraries API', 'Entity API', 'Webform', 'Metatag', 'Field Group', 'IMCE', 'CAPTCHA', 'Google Analytics', 'Redirect',
    ]);
  }

  /**
   * Tests sorting criteria.
   */
  public function testSortingCriteria(): void {
    // Clear filters.
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Clear Filters');
    $this->pressWithWait('Clear filters');

    // Select 'A-Z' sorting order.
    $this->sortBy('a_z');

    // Assert that the projects are listed in ascending order of their titles.
    $this->assertProjectsVisible([
      '(Entity Reference) Field Formatters', '@font-your-face', '*.ics field', '1000 to 1k', '200WAD API', '2mee Human Hologram Player', '3D Flipbook', '3D Image', '403 to 404', 'A Simple Timeline', 'A-Frame Extra', 'A/B Test JS',
    ]);

    // Select 'Z-A' sorting order.
    $this->sortBy('z_a');

    $this->assertProjectsVisible([
      'ЮMoney (Yandex Money) Donations', 'Сontact Mail', 'ZURB TwentyTwenty', 'ZURB Orbit', 'ZURB Foundation Sites (Library)', 'ZURB Foundation Container Layouts', 'ZURB Foundation Companion', 'Zurb Foundation 6 Paragraphs', 'Zotero CiteProc JS Server Integration', 'Zooming', 'Zoom Detect', 'Zoom API',
    ]);

    // Select 'Active installs' option.
    $this->sortBy('usage_total');

    // Assert that the projects are listed in descending order of their usage.
    $this->assertProjectsVisible([
      'Chaos Tool Suite (ctools)', 'Token', 'Pathauto', 'Libraries API', 'Entity API', 'Webform', 'Metatag', 'Field Group', 'IMCE', 'CAPTCHA', 'Google Analytics', 'Redirect',
    ]);

    // Select 'Recently created' option.
    $this->sortBy('created');

    // Assert that the projects are listed in descending order of their date of
    // creation.
    $this->assertProjectsVisible([
      'CKEditor Glossary', 'Relative Date Facets', 'External Entities BrAPI Storage plugin', 'Logout After Password Change', 'Image webp toolkit', 'Tagify', 'Webform Sign PDF Example', 'Zookeeper input filter', 'Key Dropbox', 'Fontawesome Iconpicker to Micon Converter', 'Monolog Loki for Drupal', 'Telegram Media Type',
    ]);
  }

  /**
   * Tests search with strings that need URI encoding.
   */
  public function testSearchForSpecialChar(): void {
    $this->markTestSkipped('We are using mocks of real data from Drupal.org, what we currently have does not have content suitable for this test.');
  }

  /**
   * Tests the detail page.
   */
  public function testDetailPage(): void {
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Token');
    $page->clickLink('Token');
    $this->assertTrue($assert_session->waitForText('By dries_1'));
  }

  /**
   * Tests that filtering, sorting, paging persists.
   */
  public function testPersistence(): void {
    $this->markTestSkipped('Testing this with the JSON Api endpoint is not needed. The feature is not source dependent.');
  }

  /**
   * Tests recommended filters.
   */
  public function testRecommendedFilter(): void {
    $assert_session = $this->assertSession();
    // Clear filters.
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Clear Filters');
    $this->pressWithWait('Clear filters', 'Results');
    $this->pressWithWait('Recommended filters');

    // Check that the actively maintained tag is present.
    $this->assertEquals('Maintained', $this->getElementText('p.filter-applied:nth-child(1) .filter-applied__label'));
    // Make sure the second filter applied is the security covered filter.
    $this->assertEquals('Covered by a security policy', $this->getElementText('p.filter-applied:nth-child(2) .filter-applied__label'));
    $this->assertTrue($assert_session->waitForText('4,523 Results'));
  }

  /**
   * Tests multiple source plugins at once.
   */
  public function testMultiplePlugins(): void {
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();
    // Enable module for extra source plugin.
    $this->container->get('module_installer')->install(['project_browser_devel']);
    // Test categories with multiple plugin enabled.
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('css', '.filter__categories input[type="checkbox"]');
    $assert_session->elementsCount('css', '.filter__categories input[type="checkbox"]', 54);

    $this->svelteInitHelper('css', '#project-browser .project');
    // Count tabs.
    $tab_count = $page->findAll('css', '.project-browser__plugin-tabs button');
    $this->assertCount(2, $tab_count);
    // Get result count for first tab.
    $assert_session->pageTextContains('4,523 Results');

    // Apply filters in drupalorg_mockapi(first tab).
    $assert_session->waitForElement('css', '.search__filter-button');

    $this->pressWithWait('Clear filters', '7,236 Results');

    // Click 'E-commerce' checkbox.
    $this->clickWithWait('#acc38507-ac85-43e6-8f32-beb3febea93f');

    // Click 'Commerce/Advertising' checkbox.
    $this->clickWithWait('#23d470f6-ffde-4034-a6ef-492b7121b9cf', '557 Results');

    // Filter by search text.
    $this->inputSearchField('th');
    $this->assertTrue($assert_session->waitForText('2 Results'));
    $this->assertProjectsVisible([
      'GDPR OneTrust', 'Commerce GoCardless Client',
    ]);

    // Click other tab.
    $this->pressWithWait('random_data');
    $this->svelteInitHelper('css', '.filter__categories input[type="checkbox"]');
    $assert_session->elementsCount('css', '.filter__categories input[type="checkbox"]', 20);
    $assert_session->waitForElementVisible('css', '#project-browser .project');

    // Apply the second module category filter.
    $second_category_filter_selector = '#project-browser > div.project-browser__container > .project-browser__aside > div > form > section > details > fieldset > label:nth-child(2)';
    $this->clickWithWait("$second_category_filter_selector");

    // Save the filter applied in second tab.
    $applied_filter = $this->getElementText('p.filter-applied:nth-child(1) .filter-applied__label');
    // Save the number of results.
    $results_before = count($page->findAll('css', '#project-browser .project.project--list'));

    // Switch back to first tab.
    $page->pressButton('drupalorg_jsonapi');
    // Assert that the filters persist.
    $this->assertTrue($assert_session->waitForText('2 Results'));
    $first_filter_element = $page->find('css', 'p.filter-applied:nth-child(1)');
    $this->assertEquals('Commerce/Advertising', $first_filter_element->find('css', '.filter-applied__label')->getText());
    $second_filter_element = $page->find('css', 'p.filter-applied:nth-child(2)');
    $this->assertEquals('E-commerce', $second_filter_element->find('css', '.filter-applied__label')->getText());
    $this->assertProjectsVisible([
      'GDPR OneTrust', 'Commerce GoCardless Client',
    ]);

    // Again switch to second tab.
    $page->pressButton('random_data');
    // Assert that the filters persist.
    $this->assertEquals($applied_filter, $this->getElementText('p.filter-applied:nth-child(1) .filter-applied__label'));

    // Assert that the number of results is the same.
    $results_after = count($page->findAll('css', '#project-browser .project.project--list'));
    $this->assertEquals($results_before, $results_after);
  }

  /**
   * Tests the view mode toggle keeps its state.
   */
  public function testToggleViewState(): void {
    $page = $this->getSession()->getPage();
    $viewSwitches = [
      [
        'selector' => '.project-browser__grid-button',
        'value' => 'Grid',
      ], [
        'selector' => '.project-browser__list-button',
        'value' => 'List',
      ],
    ];
    $this->getSession()->resizeWindow(1300, 1300);

    foreach ($viewSwitches as $selector) {
      $this->drupalGet('admin/modules/browse');
      $this->svelteInitHelper('css', $selector['selector']);
      $this->getSession()->getPage()->pressButton($selector['value']);
      $this->svelteInitHelper('text', 'Token');
      $page->clickLink('Token');
      $this->svelteInitHelper('text', 'Back to Browsing');
      $this->clickWithWait('.module-page--back-to-browsing');
      $this->assertSession()->elementExists('css', $selector['selector'] . '.project-browser__selected-tab');
    }
  }

  /**
   * Tests tabledrag on configuration page.
   */
  public function testTabledrag(): void {
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();
    $this->container->get('module_installer')->install(['project_browser_devel']);

    $this->drupalGet('admin/modules/browse');
    $assert_session->waitForElementVisible('css', '.project-browser__plugin-tabs button');
    // Count tabs.
    $tab_count = $page->findAll('css', '.project-browser__plugin-tabs button');
    $this->assertCount(2, $tab_count);

    // Verify that Drupal.org mockapi is first tab.
    $first_tab = $page->find('css', '.project-browser__plugin-tabs button:nth-child(1)');
    $this->assertEquals('drupalorg_jsonapi', $first_tab->getValue());

    // Re-order plugins.
    $this->drupalGet('admin/config/development/project_browser');
    $first_plugin = $page->find('css', '#source--drupalorg_jsonapi');
    $second_plugin = $page->find('css', '#source--random_data');
    $first_plugin->find('css', '.handle')->dragTo($second_plugin);
    $assert_session->assertWaitOnAjaxRequest();
    $this->submitForm([], 'Save');

    // Verify that Random data is first tab.
    $this->drupalGet('admin/modules/browse');
    $assert_session->waitForElementVisible('css', '#project-browser .project');
    $first_tab = $page->find('css', '.project-browser__plugin-tabs button:nth-child(1)');
    $this->assertEquals('random_data', $first_tab->getValue());

    // Disable Drupal.org mockapi plugin.
    $this->drupalGet('admin/config/development/project_browser');
    $enabled_row = $page->find('css', '#source--drupalorg_jsonapi');
    $disabled_region_row = $page->find('css', '.status-title-disabled');
    $enabled_row->find('css', '.handle')->dragTo($disabled_region_row);
    $assert_session->assertWaitOnAjaxRequest();
    $this->submitForm([], 'Save');
    $assert_session->pageTextContains('The configuration options have been saved.');

    // Verify that only Random data plugin is enabled.
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('css', '.filter__categories input[type="checkbox"]');
    $assert_session->elementsCount('css', '.filter__categories input[type="checkbox"]', 20);

    // Enable only Drupal.org mockapi plugin through config update.
    // It is done this way because dragging was not working reliably for enabling Drupal.org mockapi plugin.
    $this->config('project_browser.admin_settings')->set('enabled_sources', ['drupalorg_mockapi'])->save(TRUE);
    $this->drupalGet('admin/config/development/project_browser');
    $this->assertTrue($assert_session->optionExists('edit-enabled-sources-drupalorg-mockapi-status', 'enabled')->isSelected());
    $this->assertTrue($assert_session->optionExists('edit-enabled-sources-random-data-status', 'disabled')->isSelected());

    // Verify that only Drupal.org mockapi plugin is enabled.
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('css', '.filter__categories input[type="checkbox"]');
    $assert_session->elementsCount('css', '.filter__categories input[type="checkbox"]', 54);
  }

}
