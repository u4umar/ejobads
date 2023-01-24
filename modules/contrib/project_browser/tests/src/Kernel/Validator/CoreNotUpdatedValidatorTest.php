<?php

namespace Drupal\Tests\project_browser\Kernel\Validator;

use Drupal\package_manager\Event\PreApplyEvent;
use Drupal\package_manager\ValidationResult;
use Drupal\project_browser\Exception\InstallException;
use Drupal\Tests\package_manager\Kernel\PackageManagerKernelTestBase;

/**
 * @covers \Drupal\project_browser\ComposerInstaller\Validator\CoreNotUpdatedValidator
 *
 * @group project_browser
 */
class CoreNotUpdatedValidatorTest extends PackageManagerKernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['project_browser'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $active_dir = $this->container->get('package_manager.path_locator')
      ->getProjectRoot();

    $installed = __DIR__ . '/../../../fixtures/ComposerInstaller/CoreNotUpdatedValidatorTest/active.installed.json';
    $this->assertFileIsReadable($installed);
    copy($installed, $active_dir . '/vendor/composer/installed.json');
  }

  /**
   * Data provider for testPreApplyException().
   *
   * @return array[]
   *   The test cases.
   */
  public function providerPreApplyException(): array {
    $fixtures_folder = __DIR__ . '/../../../fixtures/ComposerInstaller/CoreNotUpdatedValidatorTest';

    return [
      'core updated' => [
        "$fixtures_folder/core_updated.staged.installed.json",
        ValidationResult::createError(['Drupal core has been updated in the staging area, which is not allowed by Project Browser.']),
      ],
      'core not updated' => [
        "$fixtures_folder/core_not_updated.staged.installed.json",
        NULL,
      ],
    ];
  }

  /**
   * Tests core was not updated during a project install.
   *
   * @param string $staged_installed
   *   Path of `staged.installed.json` file. It will be used as the virtual
   *   project's staged `vendor/composer/installed.json` file.
   * @param \Drupal\package_manager\ValidationResult|null $expected_result
   *   The expected validation result.
   *
   * @throws \Exception
   *
   * @dataProvider providerPreApplyException
   */
  public function testPreApplyException(string $staged_installed, ?ValidationResult $expected_result): void {
    $this->assertFileIsReadable($staged_installed);

    $listener = function (PreApplyEvent $event) use ($staged_installed): void {
      $stage_dir = $event->getStage()->getStageDirectory();
      copy($staged_installed, $stage_dir . "/vendor/composer/installed.json");
    };
    $this->container->get('event_dispatcher')->addListener(PreApplyEvent::class, $listener, PHP_INT_MAX);
    $installer = $this->container->get('project_browser.installer');
    $installer->create();
    $installer->require(['org/package-name']);
    try {
      $installer->apply();
      // If we did not get an exception, ensure we didn't expect any results.
      $this->assertNull($expected_result);
    }
    catch (InstallException $e) {
      $this->assertValidationResultsEqual([$expected_result], (array) $e->getResults());
    }
  }

}
