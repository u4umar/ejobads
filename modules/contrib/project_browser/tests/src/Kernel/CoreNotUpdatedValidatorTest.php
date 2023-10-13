<?php

namespace Drupal\Tests\project_browser\Kernel\Validator;

use Drupal\fixture_manipulator\ActiveFixtureManipulator;
use Drupal\package_manager\Exception\StageEventException;
use Drupal\package_manager\ValidationResult;
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
    (new ActiveFixtureManipulator())
      ->addPackage([
        'name' => 'org/package-name',
        'version' => '1.0.0',
        'type' => 'drupal-module',
        // We add a package and then immediately remove it to get a repository
        // entry for it, so we can 'composer require' it later.
      ])
      ->removePackage('org/package-name')
      ->commitChanges();
  }

  /**
   * Data provider for testPreApplyException().
   *
   * @return array[]
   *   The test cases.
   */
  public function providerPreApplyException(): array {
    return [
      'core updated' => [
        TRUE,
        [ValidationResult::createError(['Drupal core has been updated in the staging area, which is not allowed by Project Browser.'])],
      ],
      'core not updated' => [
        FALSE,
        [],
      ],
    ];
  }

  /**
   * Tests core was not updated during a project install.
   *
   * @param bool $core_updated
   *   Whether drupal/core was updated.
   * @param \Drupal\package_manager\ValidationResult[] $expected_results
   *   The expected validation result.
   *
   * @throws \Exception
   *
   * @dataProvider providerPreApplyException
   */
  public function testPreApplyException(bool $core_updated, array $expected_results): void {
    if ($core_updated) {
      $this->getStageFixtureManipulator()->setCorePackageVersion('9.8.1');
    }
    $installer = $this->container->get('project_browser.installer');
    $installer->create();
    $installer->require(['org/package-name']);
    try {
      $installer->apply();
      // If we did not get an exception, ensure we didn't expect any results.
      $this->assertEmpty($expected_results);
    }
    catch (StageEventException $e) {
      $this->assertValidationResultsEqual($expected_results, $e->event->getResults());
    }
  }

}
