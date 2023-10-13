<?php

namespace Drupal\Tests\disable_add_new_module\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Test disabling Add new module page.
 *
 * @group disable_add_new_module
 */
class DisableAddNewModuleTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['update', 'project_browser'];

  /**
   * A user with admin permissions.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected AccountInterface $adminUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    // Create user with access to install and update modules.
    $this->adminUser = $this->drupalCreateUser([
      'administer modules',
      'administer software updates',
      'administer site configuration',
    ]);
    $this->drupalLogin($this->adminUser);
  }

  /**
   * Test admin user denied access to Add new module page.
   */
  public function testAddNewModuleDisabled(): void {
    $session = $this->assertSession();
    $this->drupalGet('admin/modules/install');
    $session->statusCodeEquals(403);
  }

  /**
   * Test uninstalling restores access to Add new module.
   */
  public function testUninstall(): void {
    $session = $this->assertSession();
    $page = $this->getSession()->getPage();
    // Go to module uninstall page.
    $this->drupalGet('/admin/modules/uninstall');
    $session->statusCodeEquals(200);
    $page->checkField('edit-uninstall-project-browser');
    $page->pressButton('edit-submit');
    $session->statusCodeEquals(200);
    // Confirm uninstall.
    $page->pressButton('edit-submit');
    $session->statusCodeEquals(200);
    $session->pageTextContains('The selected modules have been uninstalled.');
    // Should now be able to access Add new module page.
    $this->drupalGet('admin/modules/install');
    $session->statusCodeEquals(200);
  }

  /**
   * Test setting the "disable add new module" setting.
   *
   * Test to check that "Add new module" page can be enabled through Project
   * Browser admin page.
   */
  public function testDisableSetting(): void {
    $session = $this->assertSession();
    $page = $this->getSession()->getPage();
    // Go to Project browser settings and uncheck the "disable add new module"
    // checkbox.
    $this->drupalGet('/admin/config/development/project_browser');
    $session->statusCodeEquals(200);
    $page->uncheckField('disable_add_new_module');
    $page->pressButton('edit-submit');
    $session->statusCodeEquals(200);
    $session->pageTextContains('The configuration options have been saved.');
    // Should now be able to access Add new module page.
    $this->drupalGet('admin/modules/install');
    $session->statusCodeEquals(200);
    // Go to Project browser settings, check "disable add new module" checkbox.
    $this->drupalGet('/admin/config/development/project_browser');
    $session->statusCodeEquals(200);
    $page->checkField('disable_add_new_module');
    $page->pressButton('edit-submit');
    $session->statusCodeEquals(200);
    $session->pageTextContains('The configuration options have been saved.');
    // Should no longer be able to access Add new module page.
    $this->drupalGet('admin/modules/install');
    $session->statusCodeEquals(403);
  }

}
