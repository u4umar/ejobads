<?php

namespace Drupal\project_browser\ComposerInstaller;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\TempStore\SharedTempStoreFactory;
use Drupal\package_manager\Event\StageEvent;
use Drupal\package_manager\Exception\ApplyFailedException;
use Drupal\package_manager\Exception\StageValidationException;
use Drupal\package_manager\FailureMarker;
use Drupal\package_manager\PathLocator;
use Drupal\package_manager\Stage;
use Drupal\package_manager\UnusedConfigFactory;
use Drupal\project_browser\Exception\InstallException;
use PhpTuf\ComposerStager\Domain\Core\Beginner\BeginnerInterface;
use PhpTuf\ComposerStager\Domain\Core\Committer\CommitterInterface;
use PhpTuf\ComposerStager\Domain\Core\Stager\StagerInterface;
use PhpTuf\ComposerStager\Infrastructure\Factory\Path\PathFactoryInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Defines a service to perform installs.
 *
 * @internal
 *   This is an internal part of Package Manager and may be changed or removed
 *   at any time without warning. External code should not interact with this
 *   class.
 */
final class Installer extends Stage {

  /**
   * {@inheritdoc}
   */
  public function __construct(PathLocator $path_locator, BeginnerInterface $beginner, StagerInterface $stager, CommitterInterface $committer, FileSystemInterface $file_system, EventDispatcherInterface $event_dispatcher, SharedTempStoreFactory $temp_store_factory, TimeInterface $time, PathFactoryInterface $path_factory = NULL, FailureMarker $failure_marker = NULL) {
    // 8.x-2.x
    if (class_exists(UnusedConfigFactory::class)) {
      parent::__construct(new UnusedConfigFactory(), $path_locator, $beginner, $stager, $committer, $file_system, $event_dispatcher, $temp_store_factory, $time, $path_factory, $failure_marker);
    }
    // Next major version.
    else {
      parent::__construct($path_locator, $beginner, $stager, $committer, $file_system, $event_dispatcher, $temp_store_factory, $time, $path_factory, $failure_marker);
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function dispatch(StageEvent $event, callable $on_error = NULL): void {
    try {
      parent::dispatch($event, $on_error);
    }
    catch (StageValidationException $e) {
      throw new InstallException($e->getResults(), $e->getMessage(), $e->getCode(), $e);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function apply(?int $timeout = 600): void {
    try {
      parent::apply($timeout);
    }
    catch (ApplyFailedException $exception) {
      throw new InstallException([], 'The install operation failed to apply. The install may have been partially applied. It is recommended that the site be restored from a code backup.', $exception->getCode(), $exception);
    }
  }

  /**
   * Checks if the stage tempstore lock was created by Project Browser.
   *
   * This is one of several checks performed to determine if it is acceptable
   * to destroy the current stage. Project Browser's unlock functionality uses
   * the "force" option so a stage can be destroyed even if it was created by
   * a different user or during a different session. However, a stage could have
   * been created by another module, such as Automatic Updates. In those cases
   * Project Browser should not have the ability to destroy the stage.
   *
   * This method confirms the staging lock was created by
   * Drupal\project_browser\ComposerInstaller\Installer, and will only permit
   * destroying the stage if true.
   *
   * @return bool
   *   True if the stage tempstore lock was created by Project Browser.
   */
  public function lockCameFromProjectBrowserInstaller(): bool {
    $lock_data = $this->tempStore->get(static::TEMPSTORE_LOCK_KEY);
    return !empty($lock_data[1]) && $lock_data[1] === self::class;
  }

}
