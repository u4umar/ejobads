<?php

namespace Drupal\Tests\project_browser\Kernel\Validator;

use Drupal\package_manager\ValidationResult;
use Drupal\project_browser\Exception\InstallException;
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
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $active_dir = $this->container->get('package_manager.path_locator')
      ->getProjectRoot();

    $installed = __DIR__ . '/../../../fixtures/ComposerInstaller/PackageNotInstalledValidator/active.installed.json';
    $this->assertFileIsReadable($installed);
    copy($installed, $active_dir . '/vendor/composer/installed.json');
  }

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
    try {
      /** @var \Drupal\project_browser\ComposerInstaller\Installer $installer */
      $installer = $this->container->get('project_browser.installer');
      $installer->create();
      $installer->require($packages);
      // If we did not get an exception, ensure we didn't expect any results.
      $this->assertNull($expected_result);
    }
    catch (InstallException $e) {
      $this->assertNotNull($expected_result);
      $this->assertValidationResultsEqual([$expected_result], $e->getResults());
    }
  }

}
