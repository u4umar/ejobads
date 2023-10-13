<?php

namespace Drupal\Tests\project_browser\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\Tests\project_browser\Traits\PackageManagerFixtureUtilityTrait;

/**
 * Provides tests for the Project Browser Installer UI.
 *
 * @coversDefaultClass \Drupal\project_browser\Controller\InstallerController
 *
 * @group project_browser
 */
class ProjectBrowserInstallerUiTest extends WebDriverTestBase {

  use ProjectBrowserUiTestTrait, PackageManagerFixtureUtilityTrait;

  /**
   * The shared tempstore object.
   *
   * @var \Drupal\Core\TempStore\SharedTempStore
   */
  protected $sharedTempStore;

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

    $this->initPackageManager();

    $this->sharedTempStore = $this->container->get('tempstore.shared');

    $this->config('project_browser.admin_settings')->set('enabled_sources', ['drupalorg_mockapi'])->save(TRUE);
    $this->config('project_browser.admin_settings')->set('allow_ui_install', TRUE)->save();
    $this->drupalLogin($this->drupalCreateUser([
      'administer modules',
      'administer site configuration',
    ]));
  }

  /**
   * Tests the "add and install" button functionality.
   */
  public function testModuleAddAndInstall(): void {
    $assert_session = $this->assertSession();
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Cream cheese on a bagel');
    $cream_cheese_module_selector = '#project-browser .project-browser__main ul > li:nth-child(1)';
    $download_button = $assert_session->waitForElementVisible('css', "$cream_cheese_module_selector button");
    $this->assertNotEmpty($download_button);
    $this->assertSame('Add and Install Cream cheese on a bagel', $download_button->getText());
    $download_button->click();
    $installed_action = $assert_session->waitForElementVisible('css', "$cream_cheese_module_selector .project_status-indicator");
    $assert_session->waitForText('✓ Cream cheese on a bagel is Installed');
    $this->assertSame('✓ Cream cheese on a bagel is Installed', $installed_action->getText());
  }

  /**
   * Tests the "Install" button functionality.
   *
   * The "Install" button only appears for modules in the filesystem that
   * have not been installed. This scenario is not possible if only the Project
   * Browser UI is used, but could happen if the module was added differently,
   * such as via the terminal with Compose or a direct file addition.
   */
  public function testInstallModuleAlreadyInFilesystem() {
    $assert_session = $this->assertSession();

    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Pinky and the Brain');
    $pinky_brain_selector = '#project-browser .project-browser__main ul > li:nth-child(2)';
    $action_button = $assert_session->waitForElementVisible('css', "$pinky_brain_selector button");
    $this->assertNotEmpty($action_button);
    $this->assertSame('Install Pinky and the Brain', $action_button->getText());
    $action_button->click();
    $popup = $assert_session->waitForElementVisible('css', '.project-browser-popup');
    $this->assertNotEmpty($popup);
    // The Pinky and the Brain module doesn't actually exist in the filesystem,
    // but it was registered with JavaScript as if it was to test the presence
    // of the "Install" button as opposed vs. the default "Add and Install"
    // button. This happens to be a good way to test mid-install exceptions as
    // well.
    $this->assertStringContainsString('MissingDependencyException: Unable to install modules pinky_brain due to missing modules pinky_brain', $popup->getText());

    // The action button should have momentarily changed to a progress message,
    // but changes back to the original button when the error above occurs.
    $action_button = $assert_session->waitForElementVisible('css', "$pinky_brain_selector button");
    $this->assertNotEmpty($action_button);
    $this->assertSame('Install Pinky and the Brain', $action_button->getText());
  }

  /**
   * Tests install UI not available if not enabled.
   */
  public function testAllowUiInstall(): void {
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Pinky and the Brain');

    $cream_cheese_module_selector = '#project-browser .project-browser__main ul > li:nth-child(1)';
    $download_button = $assert_session->waitForElementVisible('css', "$cream_cheese_module_selector button");
    $this->assertNotEmpty($download_button);
    $this->assertSame('Add and Install Cream cheese on a bagel', $download_button->getText());
    $this->drupalGet('/admin/config/development/project_browser');
    $page->find('css', '#edit-allow-ui-install')->click();
    $assert_session->checkboxNotChecked('edit-allow-ui-install');
    $this->submitForm([], 'Save');
    $this->assertTrue($assert_session->waitForText('The configuration options have been saved.'));

    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Cream cheese on a bagel');
    $action_button = $assert_session->waitForElementVisible('css', "$cream_cheese_module_selector button");
    $this->assertNotEmpty($action_button);
    $this->assertSame('View Commands for Cream cheese on a bagel', $action_button->getText());
  }

  /**
   * Confirms stage can be unlocked despite a missing Project Browser lock.
   *
   * @covers ::unlock
   */
  public function testCanBreakStageWithMissingProjectBrowserLock() {
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    // Start install begin.
    $this->drupalGet('admin/modules/project_browser/install-begin/drupal/metatag');
    $this->sharedTempStore->get('project_browser')->delete('requiring');
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Cream cheese on a bagel');
    // Try beginning another install while one is in progress, but not yet in
    // the applying stage.
    $cream_cheese_module_selector = '#project-browser .project-browser__main ul > li:nth-child(1)';
    $cream_cheese_button = $page->find('css', "$cream_cheese_module_selector button");
    $cream_cheese_button->click();

    $this->assertTrue($assert_session->waitForText('An install staging area claimed by Project Browser exists but has expired. You may unlock the stage and try the install again.'));

    // Click Unlock Install Stage link
    $this->clickWithWait('#ui-id-1 > p > a');
    $this->svelteInitHelper('text', 'Cream cheese on a bagel');
    // Try beginning another install after breaking lock.
    $cream_cheese_button = $page->find('css', "$cream_cheese_module_selector button");
    $cream_cheese_button->click();
    $installed_action = $assert_session->waitForElementVisible('css', "$cream_cheese_module_selector .project_status-indicator");
    $assert_session->waitForText('✓ Cream cheese on a bagel is Installed');
    $this->assertSame('✓ Cream cheese on a bagel is Installed', $installed_action->getText());

  }

  /**
   * Confirms the break lock link is available and works.
   *
   * The break lock link is not available once the stage is applying.
   *
   * @covers ::unlock
   */
  public function testCanBreakLock() {
    $assert_session = $this->assertSession();
    $page = $this->getSession()->getPage();

    // Start install begin.
    $this->drupalGet('admin/modules/project_browser/install-begin/drupal/metatag');
    $this->drupalGet('admin/modules/browse');
    $this->svelteInitHelper('text', 'Cream cheese on a bagel');
    // Try beginning another install while one is in progress, but not yet in
    // the applying stage.
    $cream_cheese_module_selector = '#project-browser .project-browser__main ul > li:nth-child(1)';
    $cream_cheese_button = $page->find('css', "$cream_cheese_module_selector button");
    $cream_cheese_button->click();
    $this->assertTrue($assert_session->waitForText('The install staging area was locked less than 1 minutes ago. This is recent enough that a legitimate installation may be in progress. Consider waiting before unlocking the installation staging area.'));
    // Click Unlock Install Stage link
    $this->clickWithWait('#ui-id-1 > p > a');
    $this->svelteInitHelper('text', 'Cream cheese on a bagel');
    // Try beginning another install after breaking lock.
    $cream_cheese_button = $page->find('css', "$cream_cheese_module_selector button");
    $cream_cheese_button->click();
    $installed_action = $assert_session->waitForElementVisible('css', "$cream_cheese_module_selector .project_status-indicator");
    $assert_session->waitForText('✓ Cream cheese on a bagel is Installed');
    $this->assertSame('✓ Cream cheese on a bagel is Installed', $installed_action->getText());

  }

}
