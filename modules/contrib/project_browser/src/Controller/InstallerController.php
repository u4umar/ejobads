<?php

namespace Drupal\project_browser\Controller;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Extension\ModuleInstallerInterface;
use Drupal\Core\TempStore\SharedTempStore;
use Drupal\Core\TempStore\SharedTempStoreFactory;
use Drupal\Core\Url;
use Drupal\package_manager\Exception\StageException;
use Drupal\project_browser\ComposerInstaller\Installer;
use Drupal\project_browser\EnabledSourceHandler;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Defines a controller to install projects via UI.
 */
class InstallerController extends ControllerBase {

  /**
   * No require or install in progress for a given module.
   *
   * @var int
   */
  protected const STATUS_IDLE = 0;

  /**
   * A staging install in progress for a given module.
   *
   * @var int
   */
  protected const STATUS_REQUIRING_PROJECT = 1;

  /**
   * A core install in progress for a given project.
   *
   * @var int
   */
  protected const STATUS_INSTALLING_PROJECT = 2;

  /**
   * The endpoint successfully returned the expected data.
   *
   * @var int
   */
  protected const STAGE_STATUS_OK = 0;

  /**
   * The Project Browser tempstore object.
   *
   * @var \Drupal\Core\TempStore\SharedTempStore
   */
  protected SharedTempStore $projectBrowserTempStore;

  public function __construct(
    private readonly Installer $installer,
    SharedTempStoreFactory $shared_temp_store_factory,
    private readonly ModuleInstallerInterface $moduleInstaller,
    private readonly EnabledSourceHandler $enabledSourceHandler,
    private readonly TimeInterface $time,
    private readonly LoggerInterface $logger,
  ) {
    $this->projectBrowserTempStore = $shared_temp_store_factory->get('project_browser');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('project_browser.installer'),
      $container->get('project_browser.tempstore.shared'),
      $container->get('module_installer'),
      $container->get('project_browser.enabled_source'),
      $container->get('datetime.time'),
      $container->get('logger.channel.project_browser'),
    );
  }

  /**
   * Checks if UI install is enabled on the site.
   */
  public function access() :AccessResult {
    $ui_install = $this->config('project_browser.admin_settings')->get('allow_ui_install');
    return AccessResult::allowedIf((bool) $ui_install);
  }

  /**
   * Nulls the installing and core installing states.
   */
  private function resetProgress(): void {
    $this->projectBrowserTempStore->delete('requiring');
    $this->projectBrowserTempStore->delete('installing');
  }

  /**
   * Resets progress and destroys the stage.
   */
  private function cancelRequire(): void {
    $this->resetProgress();
    // Checking the for the presence of a lock in the package manager stage is
    // necessary as this method can be called during create(), which includes both
    // the PreCreate and PostCreate events. If an exception is caught during
    // PreCreate, there's no stage to destroy and an exception would be raised.
    // So, we check for the presence of a stage before calling destroy().
    if (!$this->installer->isAvailable() && $this->installer->lockCameFromProjectBrowserInstaller()) {
      // The risks of forcing a destroy with TRUE are understood, which is why
      // we first check if the lock originated from Project Browser. This
      // function is called if an exception is thrown during an install. This
      // can occur during a phase where the stage might not be claimable, so we
      // force-destroy with the TRUE parameter, knowing that the checks above
      // will prevent destroying an Automatic Updates stage or a stage that is
      // in the process of applying.
      $this->installer->destroy(TRUE);
    }
  }

  /**
   * Returns the status of the project in the temp store.
   *
   * @param string $project_id
   *   The project machine name.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Information about the project's require/install status.
   *
   *   If the project is being required, the response will include which require
   *   phase is currently occurring.
   *
   *   When a project is required via the UI, the UI fetches this endpoint
   *   regularly so it can monitor the progress of the process and report which
   *   stage is taking place.
   */
  public function inProgress(string $project_id): JsonResponse {
    $requiring = $this->projectBrowserTempStore->get('requiring');
    $core_installing = $this->projectBrowserTempStore->get('installing');
    $return = ['status' => self::STATUS_IDLE];

    if (isset($requiring['project_id']) && $requiring['project_id'] === $project_id) {
      $return['status'] = self::STATUS_REQUIRING_PROJECT;
      $return['phase'] = $requiring['phase'];
    }
    if ($core_installing === $project_id) {
      $return['status'] = self::STATUS_INSTALLING_PROJECT;
    }

    return new JsonResponse($return);
  }

  /**
   * Provides a JSON response for a given error.
   *
   * @param \Exception $e
   *   The error that occurred.
   * @param string $phase
   *   The phase the error occurred in.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Provides an error message to be displayed by the Project Browser UI.
   */
  private function errorResponse(\Exception $e, string $phase = ''): JsonResponse {
    $exception_type_short = (new \ReflectionClass($e))->getShortName();
    $exception_message = $e->getMessage();
    $response_body = ['message' => "$exception_type_short: $exception_message"];
    $this->logger->warning('@exception_type: @exception_message. @trace ', [
      '@exception_type' => get_class($e),
      '@exception_message' => $exception_message,
      '@trace' => $e->getTraceAsString(),
    ]);

    if (!empty($phase)) {
      $response_body['phase'] = $phase;
    }
    return new JsonResponse($response_body, 500);
  }

  /**
   * Provides a JSON response for a successful request.
   *
   * @param string $phase
   *   The phase the request was made in.
   * @param string|null $stage_id
   *   The stage id of the installer within the request.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Provides information about the completed operation.
   */
  private function successResponse(string $phase, ?string $stage_id = NULL): JsonResponse {
    $response_body = [
      'phase' => $phase,
      'status' => self::STAGE_STATUS_OK,
    ];
    if (!empty($stage_id)) {
      $response_body['stage_id'] = $stage_id;
    }
    return new JsonResponse($response_body);
  }

  /**
   * Provides a JSON response for require requests while the stage is locked.
   *
   * @param string $message
   *   The message content of the response.
   * @param string $unlock_url
   *   An unlock url provided in instances where unlocking is safe.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Provides a message regarding the status of the staging lock.
   *
   *   If the stage is not in a phase where it is unsafe to unlock, a CSRF
   *   protected unlock URL is also provided.
   */
  private function lockedResponse(string $message, string $unlock_url = ''): JsonResponse {
    return new JsonResponse([
      'message' => $message,
      'unlock_url' => $unlock_url,
    ], 418);
  }

  /**
   * Updates the 'requiring' state in the temp store.
   *
   * @param string $project_id
   *   The module being required.
   * @param string $phase
   *   The require phase in progress.
   * @param string $stage_id
   *   The stage id.
   */
  private function setRequiringState(string $project_id, string $phase, string $stage_id = ''): void {
    $this->projectBrowserTempStore->set('requiring', [
      'project_id' => $project_id,
      'phase' => $phase,
      'stage_id' => $stage_id,
    ]);
  }

  /**
   * Unlocks and destroys the stage.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
   *   Redirects to the main project browser page.
   *
   * @todo add return type when php 7.4 support ends.
   */
  public function unlock() {
    try {
      // It's possible the unlock url was provided before applying began, but
      // accessed after. This final check ensures a destroy is not attempted
      // during apply.
      if ($this->installer->isApplying()) {
        throw new StageException('A stage can not be unlocked while applying');
      }

      // Adding the TRUE parameter to destroy is dangerous, but we provide it
      // here for a few reasons.
      // - This endpoint is only available if it's confirmed the stage lock was
      //   created by  Drupal\project_browser\ComposerInstaller\Installer.
      // - This endpoint is not available if the stage is applying.
      // - In the event of a flawed install, we want it to be possible for users
      //   to unlock the stage via the GUI, even if they're not the user that
      //   initiated the install.
      // - The unlock link is accompanied by information regarding when the
      //   stage was locked, and warns the user when the time is recent enough
      //   that they risk aborting a legitimate install.
      $this->installer->destroy(TRUE);
    }
    catch (\Exception $e) {
      return $this->errorResponse($e);
    }
    $this->projectBrowserTempStore->delete('requiring');
    $this->messenger()->addStatus($this->t('Install staging area unlocked.'));
    return $this->redirect('project_browser.browse');
  }

  /**
   * Gets the given URL with all placeholders replaced.
   *
   * @param \Drupal\Core\Url $url
   *   A URL which generates CSRF token placeholders.
   *
   * @return string
   *   The URL string, with all placeholders replaced.
   */
  private static function getUrlWithReplacedCsrfTokenPlaceholder(Url $url): string {
    $generated_url = $url->toString(TRUE);
    $url_with_csrf_token_placeholder = [
      '#plain_text' => $generated_url->getGeneratedUrl(),
    ];
    $generated_url->applyTo($url_with_csrf_token_placeholder);
    return (string) \Drupal::service('renderer')->renderPlain($url_with_csrf_token_placeholder);
  }

  /**
   * Begins requiring by creating a stage.
   *
   * @param string $composer_namespace
   *   The project composer namespace.
   * @param string $project_id
   *   The project id.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Status message.
   */
  public function begin(string $composer_namespace, string $project_id): JsonResponse {
    // @todo Expand to support other plugins in https://drupal.org/i/3312354.
    $source = $this->enabledSourceHandler->getCurrentSources()['drupalorg_mockapi'] ?? NULL;
    if ($source === NULL) {
      return new JsonResponse(['message' => "Cannot download $project_id from any available source"], 500);
    }
    if (!$source->isProjectSafe($project_id)) {
      return new JsonResponse(['message' => "$project_id is not safe to add because its security coverage has been revoked"], 500);
    }
    $stage_available = $this->installer->isAvailable();
    if (!$stage_available) {
      $requiring_metadata = $this->projectBrowserTempStore->getMetadata('requiring');
      if (!$this->installer->lockCameFromProjectBrowserInstaller()) {
        return $this->lockedResponse($this->t('The installation stage is locked by a process outside of Project Browser'), '');
      }
      if (empty($requiring_metadata)) {
        $unlock_url = self::getUrlWithReplacedCsrfTokenPlaceholder(
          Url::fromRoute('project_browser.install.unlock')
        );
        $message = t('An install staging area claimed by Project Browser exists but has expired. You may unlock the stage and try the install again.');
        return $this->lockedResponse($message, $unlock_url);
      }
      $time_since_updated = $this->time->getRequestTime() - $requiring_metadata->getUpdated();
      $hours = (int) gmdate("H", $time_since_updated);
      $minutes = (int) gmdate("i", $time_since_updated);
      $minutes = $time_since_updated > 60 ? $minutes : 'less than 1';
      if ($this->installer->isApplying()) {
        $message = empty(floor($hours)) ?
          $this->t('The install staging area was locked @minutes minutes ago. It should not be unlocked as the changes from staging are being applied to the site.', ['@minutes' => $minutes]) :
          $this->t('The install staging area was locked @hours hours, @minutes minutes ago. It should not be unlocked as the changes from staging are being applied to the site.', ['@hours' => $hours, '@minutes' => $minutes]);
        return $this->lockedResponse($message, '');

      }
      elseif ($hours === 0 && ($minutes < 7 || $minutes === 'less than 1')) {
        $message = $this->t('The install staging area was locked @minutes minutes ago. This is recent enough that a legitimate installation may be in progress. Consider waiting before unlocking the installation staging area.', ['@minutes' => $minutes]);
      }
      else {
        $message = empty($hours) ?
          $this->t('The install staging area was locked @minutes minutes ago.', ['@minutes' => $minutes]) :
          $this->t('The install staging area was locked @hours hours, @minutes minutes ago.', ['@hours' => $hours, '@minutes' => $minutes]);
      }

      $unlock_url = self::getUrlWithReplacedCsrfTokenPlaceholder(
        Url::fromRoute('project_browser.install.unlock')
      );
      return $this->lockedResponse($message, $unlock_url);
    }

    try {
      $stage_id = $this->installer->create();
      $this->setRequiringState($project_id, 'creating install stage', $stage_id);
    }
    catch (\Exception $e) {
      $this->cancelRequire();
      return $this->errorResponse($e, 'create');
    }

    return $this->successResponse('create', $stage_id);
  }

  /**
   * Performs require operations on the stage.
   *
   * @param string $composer_namespace
   *   The project composer namespace.
   * @param string $project_id
   *   The project id.
   * @param string $stage_id
   *   ID of stage created in the begin() method.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Status message.
   */
  public function require(string $composer_namespace, string $project_id, string $stage_id): JsonResponse {
    $requiring = $this->projectBrowserTempStore->get('requiring');
    if (empty($requiring['project_id']) || $requiring['project_id'] !== $project_id) {
      return new JsonResponse([
        'message' => sprintf('Error: a request to install %s was ignored as an install for a different module is in progress.', $project_id),
      ], 500);
    }
    $this->setRequiringState($project_id, 'requiring module', $stage_id);
    try {
      $this->installer->claim($stage_id)->require(["$composer_namespace/$project_id"]);
    }
    catch (\Exception $e) {
      $this->cancelRequire();
      return $this->errorResponse($e, 'require');
    }
    return $this->successResponse('require', $stage_id);
  }

  /**
   * Performs apply operations on the stage.
   *
   * @param string $composer_namespace
   *   The project composer namespace.
   * @param string $project_id
   *   The project id.
   * @param string $stage_id
   *   ID of stage created in the begin() method.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Status message.
   */
  public function apply(string $composer_namespace, string $project_id, string $stage_id): JsonResponse {
    $this->setRequiringState($project_id, 'applying', $stage_id);
    try {
      $this->installer->claim($stage_id)->apply();
    }
    catch (\Exception $e) {
      $this->cancelRequire();
      return $this->errorResponse($e, 'apply');
    }
    return $this->successResponse('apply', $stage_id);
  }

  /**
   * Performs post apply operations on the stage.
   *
   * @param string $composer_namespace
   *   The project composer namespace.
   * @param string $project_id
   *   The project id.
   * @param string $stage_id
   *   ID of stage created in the begin() method.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Status message.
   */
  public function postApply(string $composer_namespace, string $project_id, string $stage_id): JsonResponse {
    $this->setRequiringState($project_id, 'post apply', $stage_id);
    try {
      $this->installer->claim($stage_id)->postApply();
    }
    catch (\Exception $e) {
      return $this->errorResponse($e, 'post apply');
    }
    return $this->successResponse('post apply', $stage_id);
  }

  /**
   * Performs destroy operations on the stage.
   *
   * @param string $composer_namespace
   *   The project composer namespace.
   * @param string $project_id
   *   The project id.
   * @param string $stage_id
   *   ID of stage created in the begin() method.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Status message.
   */
  public function destroy(string $composer_namespace, string $project_id, string $stage_id): JsonResponse {
    $this->setRequiringState($project_id, 'completing', $stage_id);
    try {
      $this->installer->claim($stage_id)->destroy();
    }
    catch (\Exception $e) {
      return $this->errorResponse($e, 'destroy');
    }
    $this->projectBrowserTempStore->delete('requiring');
    return new JsonResponse([
      'phase' => 'destroy',
      'status' => self::STAGE_STATUS_OK,
      'stage_id' => $stage_id,
    ]);
  }

  /**
   * Installs an already downloaded module.
   *
   * @param string $project_id
   *   The project machine name.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Status message.
   */
  public function activateModule(string $project_id): JsonResponse {
    $this->projectBrowserTempStore->set('installing', $project_id);
    try {
      $this->moduleInstaller->install([$project_id]);
    }
    catch (\Exception $e) {
      $this->resetProgress();
      return $this->errorResponse($e, 'project install');
    }
    $this->projectBrowserTempStore->delete('installing');
    return new JsonResponse([
      'status' => 0,
    ]);
  }

}
