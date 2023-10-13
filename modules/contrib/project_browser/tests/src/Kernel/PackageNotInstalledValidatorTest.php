<?php

namespace Drupal\Tests\project_browser\Kernel\Validator;

use Drupal\fixture_manipulator\ActiveFixtureManipulator;
use Drupal\package_manager\Exception\StageEventException;
use Drupal\package_manager\ValidationResult;
use Drupal\Tests\package_manager\Kernel\PackageManagerKernelTestBase;

/**
 * @covers \Drupal\project_browser\ComposerInstaller\Validator\PackageNotInstalledValidator
 *
 * @group project_browser
 */
class PackageNotInstalledValidatorTest extends PackageManagerKernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'project_browser',
  ];

  /**
   * Data provider for testPreRequireException().
   *
   * @return array[]
   *   The test cases.
   */
  public function providerPreRequireException(): array {
    $summary = t('The following package is already installed:');
    $summary_plural = t('The following packages are already installed:');

    return [
      'new package which is currently *not* installed' => [
        ['drupal/new_module'],
        NULL,
      ],
      'already installed package' => [
        ['drupal/my_module'],
        ValidationResult::createError(['drupal/my_module'], $summary),
      ],
      '2 packages sent, 1 is already installed' => [
        ['drupal/new_module', 'drupal/my_module'],
        ValidationResult::createError(['drupal/my_module'], $summary),
      ],
      '2 packages sent, both already installed' => [
        ['drupal/my_module', 'drupal/my_module_2'],
        ValidationResult::createError(['drupal/my_module', 'drupal/my_module_2'], $summary_plural),
      ],
    ];
  }

  /**
   * Tests the packages installed with Composer during pre-create.
   *
   * @param string[] $packages
   *   The packages to install.
   * @param \Drupal\package_manager\ValidationResult|null $expected_result
   *   The expected validation result if any, otherwise NULL.
   *
   * @dataProvider providerPreRequireException
   */
  public function testPreRequireException(array $packages, ?ValidationResult $expected_result): void {
    (new ActiveFixtureManipulator())
      ->addPackage([
        'name' => 'drupal/my_module',
        'version' => '9.8.0',
        'type' => 'drupal-module',
      ])
      ->addPackage([
        'name' => 'drupal/my_module_2',
        'version' => '9.8.0',
        'type' => 'drupal-module',
      ])
      ->addPackage([
        'name' => 'drupal/my_dev_module',
        'version' => '9.8.1',
        'type' => 'drupal-module',
      ], TRUE)
      ->addPackage([
        'name' => 'drupal/new_module',
        'version' => '9.8.3',
        'type' => 'drupal-module',
      ])
      // We add a package and then immediately remove it to get a repository
      // entry for it, so we can 'composer require' it later.
      ->removePackage('drupal/new_module')
      ->commitChanges();
    /** @var \Drupal\project_browser\ComposerInstaller\Installer $installer */
    $installer = $this->container->get('project_browser.installer');
    try {
      $installer->create();
      $installer->require($packages);
      // If we did not get an exception, ensure we didn't expect any results.
      $this->assertNull($expected_result);
    }
    catch (StageEventException $e) {
      $this->assertNotNull($expected_result);
      $this->assertValidationResultsEqual([$expected_result], $e->event->getResults());
    }
  }

}
