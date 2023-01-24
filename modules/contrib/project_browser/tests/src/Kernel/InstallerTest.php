<?php

namespace Drupal\Tests\project_browser\Kernel;

use Drupal\package_manager\Event\PreApplyEvent;
use Drupal\package_manager\Exception\StageException;
use Drupal\package_manager\ValidationResult;
use Drupal\package_manager_bypass\Committer;
use Drupal\package_manager_test_validation\EventSubscriber\TestSubscriber;
use Drupal\project_browser\Exception\InstallException;
use Drupal\Tests\package_manager\Kernel\PackageManagerKernelTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;
use PhpTuf\ComposerStager\Domain\Exception\InvalidArgumentException;

/**
 * @coversDefaultClass \Drupal\project_browser\ComposerInstaller\Installer
 *
 * @group project_browser
 */
class InstallerTest extends PackageManagerKernelTestBase {

  use UserCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'package_manager_test_validation',
    'project_browser',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installEntitySchema('user');
  }

  /**
   * Data provider for testCommitException().
   *
   * @return \string[][]
   *   The test cases.
   */
  public function providerCommitException(): array {
    return [
      'RuntimeException' => [
        'RuntimeException',
        InstallException::class,
      ],
      'InvalidArgumentException' => [
        InvalidArgumentException::class,
        StageException::class,
      ],
      'Exception' => [
        'Exception',
        InstallException::class,
      ],
    ];
  }

  /**
   * Tests exception handling during calls to Composer Stager commit.
   *
   * @param string $thrown_class
   *   The throwable class that should be thrown by Composer Stager.
   * @param string|null $expected_class
   *   The expected exception class.
   *
   * @dataProvider providerCommitException
   */
  public function testCommitException(string $thrown_class, string $expected_class = NULL): void {
    /** @var \Drupal\project_browser\ComposerInstaller\Installer $installer */
    $installer = $this->container->get('project_browser.installer');
    $installer->create();
    $installer->require(['org/package-name']);
    $thrown_message = 'A very bad thing happened';
    Committer::setException(new $thrown_class($thrown_message, 123));
    $this->expectException($expected_class);
    $expected_message = $expected_class === InstallException::class ?
      'The install operation failed to apply. The install may have been partially applied. It is recommended that the site be restored from a code backup.'
      : $thrown_message;
    $this->expectExceptionMessage($expected_message);
    $this->expectExceptionCode(123);
    $installer->apply();
  }

  /**
   * Tests that validation errors are thrown as install exceptions.
   *
   * @covers ::dispatch
   */
  public function testInstallException() {
    /** @var \Drupal\project_browser\ComposerInstaller\Installer $installer */
    $installer = $this->container->get('project_browser.installer');
    $installer->create();
    $installer->require(['org/package-name']);
    $results = [
      ValidationResult::createError([t('These are not the projects you are looking for.')]),
    ];
    TestSubscriber::setTestResult($results, PreApplyEvent::class);
    $this->expectException(InstallException::class);
    $this->expectExceptionMessage('These are not the projects you are looking for.');
    $installer->apply();
  }

}
