<?php

namespace Drupal\Tests\project_browser\Kernel;

use Drupal\fixture_manipulator\ActiveFixtureManipulator;
use Drupal\package_manager\Event\PreApplyEvent;
use Drupal\package_manager\Exception\ApplyFailedException;
use Drupal\package_manager\Exception\StageEventException;
use Drupal\package_manager\Exception\StageException;
use Drupal\package_manager\ValidationResult;
use Drupal\package_manager_bypass\LoggingCommitter;
use Drupal\package_manager_test_validation\EventSubscriber\TestSubscriber;
use Drupal\Tests\package_manager\Kernel\PackageManagerKernelTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;
use PhpTuf\ComposerStager\API\Exception\InvalidArgumentException;
use PhpTuf\ComposerStager\Internal\Translation\Value\TranslatableMessage;

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
    (new ActiveFixtureManipulator())
      ->addPackage([
        'name' => 'org/package-name',
        'version' => '9.8.3',
        'type' => 'drupal-module',
      ])
      ->removePackage('org/package-name')->commitChanges();
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
        ApplyFailedException::class,
      ],
      'InvalidArgumentException' => [
        InvalidArgumentException::class,
        StageException::class,
      ],
      'Exception' => [
        'Exception',
        ApplyFailedException::class,
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
    $thrown_message = new TranslatableMessage('A very bad thing happened');
    LoggingCommitter::setException(new $thrown_class($thrown_message, 123));
    $this->expectException($expected_class);
    $expected_message = $expected_class === ApplyFailedException::class ?
      'Staged changes failed to apply, and the site is in an indeterminate state. It is strongly recommended to restore the code and database from a backup.'
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
    $this->expectException(StageEventException::class);
    $this->expectExceptionMessage('These are not the projects you are looking for.');
    $installer->apply();
  }

}
