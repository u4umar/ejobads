<?php

namespace Drupal\Tests\project_browser\Functional;

use Drupal\package_manager\Event\StatusCheckEvent;
use Drupal\package_manager_test_validation\EventSubscriber\TestSubscriber;
use Drupal\package_manager\ValidationResult;

/**
 * Tests the installer readiness controller.
 *
 * @group project_browser
 */
class InstallReadinessControllerTest extends ProjectBrowserInstallerFunctionalTestBase {

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
  protected function setUp(): void {
    parent::setUp();
    $this->drupalLogin($this->drupalCreateUser(['administer modules']));
  }

  /**
   * Tests the readiness controller response when all checks pass.
   */
  public function testControllerReady() {
    $this->drupalGet('admin/modules/project_browser/install-readiness');
    $this->assertSame('{"pm_validation":false,"stage_available":true}', $this->getSession()->getPage()->getContent());
  }

  /**
   * Tests the readiness controller when a check does not pass.
   */
  public function testControllerNotReady() {
    $message = t('Someone spilled coffee on your website.');
    $result = ValidationResult::createError([$message]);
    TestSubscriber::setTestResult([$result], StatusCheckEvent::class);
    $this->drupalGet('admin/modules/project_browser/install-readiness');
    $this->assertSame('{"pm_validation":"Someone spilled coffee on your website.\n","stage_available":true}', $this->getSession()->getPage()->getContent(), 'Expected readiness endpoint to report error set by subscriber.');
  }

  /**
   * Tests that the stage_available property reports an unavailable stage.
   */
  public function testStageNotAvailable() {
    /** @var \Drupal\project_browser\ComposerInstaller\Installer $installer */
    $installer = $this->container->get('project_browser.installer');
    $installer->create();
    $this->drupalGet('admin/modules/project_browser/install-readiness');
    $this->assertSame('{"pm_validation":false,"stage_available":false}', $this->getSession()->getPage()->getContent(), 'Expected readiness endpoint to report that stage is not available.');
  }

}
