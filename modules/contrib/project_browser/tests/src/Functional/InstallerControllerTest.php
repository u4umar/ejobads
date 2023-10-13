<?php

namespace Drupal\Tests\project_browser\Functional;

// cspell:ignore crashmore

use Drupal\Component\Serialization\Json;
use Drupal\package_manager\ValidationResult;
use Drupal\package_manager_test_validation\EventSubscriber\TestSubscriber;
use Drupal\package_manager\Event\PreCreateEvent;
use Drupal\package_manager\Event\PreRequireEvent;
use Drupal\package_manager\Event\PreApplyEvent;
use Drupal\package_manager\Event\PreDestroyEvent;
use Drupal\package_manager\Event\PostApplyEvent;
use Drupal\package_manager\Event\PostCreateEvent;
use Drupal\package_manager\Event\PostRequireEvent;
use Drupal\package_manager\Event\PostDestroyEvent;
use Drupal\project_browser_test\Datetime\TestTime;
use Drupal\Tests\BrowserTestBase;
use Drupal\Tests\project_browser\Traits\PackageManagerFixtureUtilityTrait;

/**
 * Tests the installer controller.
 *
 * @coversDefaultClass \Drupal\project_browser\Controller\InstallerController
 *
 * @group project_browser
 */
class InstallerControllerTest extends BrowserTestBase {

  use PackageManagerFixtureUtilityTrait;

  /**
   * The shared tempstore object.
   *
   * @var \Drupal\Core\TempStore\SharedTempStore
   */
  protected $sharedTempStore;

  /**
   * A stage id.
   *
   * @var string
   */
  protected $stageId;

  /**
   * The installer.
   *
   * @var \Drupal\project_browser\ComposerInstaller\Installer
   */
  private $installer;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'project_browser',
    'project_browser_test',
    'system',
    'user',
  ];

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  protected function setUp(): void {
    parent::setUp();
    $connection = $this->container->get('database');
    $query = $connection->insert('project_browser_projects')->fields([
      'nid',
      'title',
      'author',
      'created',
      'changed',
      'project_usage_total',
      'maintenance_status',
      'development_status',
      'status',
      'field_security_advisory_coverage',
      'flag_project_star_user_count',
      'field_project_type',
      'project_data',
      'field_project_machine_name',
    ]);
    $query->values([
      'nid' => 111,
      'title' => 'An Awesome Module',
      'author' => 'Detective Crashmore',
      'created' => 1383917647,
      'changed' => 1663534145,
      'project_usage_total' => 455,
      'maintenance_status' => 13028,
      'development_status' => 9988,
      'status' => 1,
      'field_security_advisory_coverage' => 'covered',
      'flag_project_star_user_count' => 0,
      'field_project_type' => 'full',
      'project_data' => serialize([]),
      'field_project_machine_name' => 'awesome_module',
    ]);
    $query->values([
      'nid' => 222,
      'title' => 'Security Revoked Module',
      'author' => 'Jamie Taco',
      'created' => 1383917448,
      'changed' => 1663534145,
      'project_usage_total' => 455,
      'maintenance_status' => 13028,
      'development_status' => 9988,
      'status' => 1,
      'field_security_advisory_coverage' => 'covered',
      'flag_project_star_user_count' => 0,
      'field_project_type' => 'full',
      'project_data' => serialize([]),
      'field_project_machine_name' => 'awesome_module',
    ]);
    $query->execute();
    $this->initPackageManager();
    $this->sharedTempStore = $this->container->get('tempstore.shared');
    $this->installer = $this->container->get('project_browser.installer');
    $this->drupalLogin($this->drupalCreateUser(['administer modules']));
    $this->config('project_browser.admin_settings')->set('allow_ui_install', TRUE)->save();
  }

  /**
   * Confirms install endpoint not available if UI installs are not enabled.
   *
   * @covers ::access
   */
  public function testUiInstallUnavailableIfDisabled() {
    $this->config('project_browser.admin_settings')->set('allow_ui_install', FALSE)->save();
    $this->drupalGet('admin/modules/project_browser/install-begin/drupal/awesome_module');
    $this->assertSession()->statusCodeEquals(403);
    $this->assertSession()->pageTextContains('Access denied');
  }

  /**
   * Confirms prevention of requiring modules with revoked security status.
   *
   * @covers ::begin
   */
  public function testInstallSecurityRevokedModule() {
    $this->assertProjectBrowserTempStatus(NULL, NULL);
    $content = $this->drupalGet('admin/modules/project_browser/install-begin/drupal/security_revoked_module');
    $this->assertSession()->statusCodeEquals(500);
    $this->assertSame('{"message":"security_revoked_module is not safe to add because its security coverage has been revoked"}', $content);
  }

  /**
   * Confirms a require will stop if package already present.
   *
   * @covers ::require
   */
  public function testInstallAlreadyPresentPackage() {
    $this->assertProjectBrowserTempStatus(NULL, NULL);
    // Though core is not available as a choice in project browser, it works
    // well for the purposes of this test as it's definitely already added
    // via composer.
    $this->drupalGet('admin/modules/project_browser/install-begin/drupal/core');
    $this->stageId = $this->sharedTempStore->get('package_manager_stage')->get('lock')[0];
    $content = $this->drupalGet("/admin/modules/project_browser/install-require/drupal/core/$this->stageId");
    $this->assertSession()->statusCodeEquals(500);
    $this->assertSame('{"message":"StageEventException: The following package is already installed: drupal\/core\n","phase":"require"}', $content);
  }

  /**
   * Calls the endpoint that begins installation.
   *
   * @covers ::begin
   */
  private function doStart() {
    $this->assertProjectBrowserTempStatus(NULL, NULL);
    $this->drupalGet('admin/modules/project_browser/install-begin/drupal/awesome_module');
    $this->stageId = $this->sharedTempStore->get('package_manager_stage')->get('lock')[0];
    $this->assertSession()->statusCodeEquals(200);
    $expected_output = sprintf('{"phase":"create","status":0,"stage_id":"%s"}', $this->stageId);
    $this->assertSame($expected_output, $this->getSession()->getPage()->getContent());
    $this->assertInstallInProgress('awesome_module', 'creating install stage');
  }

  /**
   * Calls the endpoint that continues to the require phase of installation.
   *
   * @covers ::require
   */
  private function doRequire() {
    $this->drupalGet("/admin/modules/project_browser/install-require/drupal/awesome_module/$this->stageId");
    $expected_output = sprintf('{"phase":"require","status":0,"stage_id":"%s"}', $this->stageId);
    $this->assertSame($expected_output, $this->getSession()->getPage()->getContent());
    $this->assertInstallInProgress('awesome_module', 'requiring module');
  }

  /**
   * Calls the endpoint that continues to the apply phase of installation.
   *
   * @covers ::apply
   */
  private function doApply() {
    $this->drupalGet("/admin/modules/project_browser/install-apply/drupal/awesome_module/$this->stageId");
    $expected_output = sprintf('{"phase":"apply","status":0,"stage_id":"%s"}', $this->stageId);
    $this->assertSame($expected_output, $this->getSession()->getPage()->getContent());
    $this->assertInstallInProgress('awesome_module', 'applying');
  }

  /**
   * Calls the endpoint that continues to the post apply phase of installation.
   *
   * @covers ::postApply
   */
  private function doPostApply() {
    $this->drupalGet("/admin/modules/project_browser/install-post_apply/drupal/awesome_module/$this->stageId");
    $expected_output = sprintf('{"phase":"post apply","status":0,"stage_id":"%s"}', $this->stageId);
    $this->assertSame($expected_output, $this->getSession()->getPage()->getContent());
    $this->assertInstallInProgress('awesome_module', 'post apply');
  }

  /**
   * Calls the endpoint that continues to the destroy phase of installation.
   *
   * @covers ::destroy
   */
  private function doDestroy() {
    $this->drupalGet("/admin/modules/project_browser/install-destroy/drupal/awesome_module/$this->stageId");
    $expected_output = sprintf('{"phase":"destroy","status":0,"stage_id":"%s"}', $this->stageId);
    $this->assertSame($expected_output, $this->getSession()->getPage()->getContent());
    $this->assertInstallNotInProgress('awesome_module');
  }

  /**
   * Calls every endpoint needed to do a UI install and confirms they work.
   */
  public function testUiInstallerEndpoints() {
    $this->doStart();
    $this->doRequire();
    $this->doApply();
    $this->doPostApply();
    $this->doDestroy();
  }

  /**
   * Tests an error during a pre create event.
   *
   * @covers ::create
   */
  public function testPreCreateError() {
    $message = t('This is a PreCreate error.');
    $result = ValidationResult::createError([$message]);
    TestSubscriber::setTestResult([$result], PreCreateEvent::class);
    $contents = $this->drupalGet('admin/modules/project_browser/install-begin/drupal/awesome_module');
    $this->assertSession()->statusCodeEquals(500);
    $this->assertSame('{"message":"StageEventException: This is a PreCreate error.\n","phase":"create"}', $contents);
  }

  /**
   * Tests an exception during a pre create event.
   *
   * @covers ::create
   */
  public function testPreCreateException() {
    $error = new \Exception('PreCreate did not go well.');
    TestSubscriber::setException($error, PreCreateEvent::class);
    $contents = $this->drupalGet('admin/modules/project_browser/install-begin/drupal/awesome_module');
    $this->assertSession()->statusCodeEquals(500);
    $this->assertSame('{"message":"StageEventException: PreCreate did not go well.","phase":"create"}', $contents);
  }

  /**
   * Tests an exception during a post create event.
   *
   * @covers ::create
   */
  public function testPostCreateException() {
    $error = new \Exception('PostCreate did not go well.');
    TestSubscriber::setException($error, PostCreateEvent::class);
    $contents = $this->drupalGet('admin/modules/project_browser/install-begin/drupal/awesome_module');
    $this->assertSession()->statusCodeEquals(500);
    $this->assertSame('{"message":"StageEventException: PostCreate did not go well.","phase":"create"}', $contents);
  }

  /**
   * Tests an error during a pre require event.
   *
   * @covers ::require
   */
  public function testPreRequireError() {
    $message = t('This is a PreRequire error.');
    $result = ValidationResult::createError([$message]);
    $this->doStart();
    TestSubscriber::setTestResult([$result], PreRequireEvent::class);
    $contents = $this->drupalGet("/admin/modules/project_browser/install-require/drupal/awesome_module/$this->stageId");
    $this->assertSession()->statusCodeEquals(500);
    $this->assertSame('{"message":"StageEventException: This is a PreRequire error.\n","phase":"require"}', $contents);
  }

  /**
   * Tests an exception during a pre require event.
   *
   * @covers ::require
   */
  public function testPreRequireException() {
    $error = new \Exception('PreRequire did not go well.');
    TestSubscriber::setException($error, PreRequireEvent::class);
    $this->doStart();
    $contents = $this->drupalGet("/admin/modules/project_browser/install-require/drupal/awesome_module/$this->stageId");
    $this->assertSession()->statusCodeEquals(500);
    $this->assertSame('{"message":"StageEventException: PreRequire did not go well.","phase":"require"}', $contents);
  }

  /**
   * Tests an exception during a post require event.
   *
   * @covers ::require
   */
  public function testPostRequireException() {
    $error = new \Exception('PostRequire did not go well.');
    TestSubscriber::setException($error, PostRequireEvent::class);
    $this->doStart();
    $contents = $this->drupalGet("/admin/modules/project_browser/install-require/drupal/awesome_module/$this->stageId");
    $this->assertSession()->statusCodeEquals(500);
    $this->assertSame('{"message":"StageEventException: PostRequire did not go well.","phase":"require"}', $contents);
  }

  /**
   * Tests an error during a pre apply event.
   *
   * @covers ::apply
   */
  public function testPreApplyError() {
    $message = t('This is a PreApply error.');
    $result = ValidationResult::createError([$message]);
    TestSubscriber::setTestResult([$result], PreApplyEvent::class);
    $this->doStart();
    $this->doRequire();
    $contents = $this->drupalGet("/admin/modules/project_browser/install-apply/drupal/awesome_module/$this->stageId");
    $this->assertSession()->statusCodeEquals(500);
    $this->assertSame('{"message":"StageEventException: This is a PreApply error.\n","phase":"apply"}', $contents);
  }

  /**
   * Tests an exception during a pre apply event.
   *
   * @covers ::apply
   */
  public function testPreApplyException() {
    $error = new \Exception('PreApply did not go well.');
    TestSubscriber::setException($error, PreApplyEvent::class);
    $this->doStart();
    $this->doRequire();
    $contents = $this->drupalGet("/admin/modules/project_browser/install-apply/drupal/awesome_module/$this->stageId");
    $this->assertSession()->statusCodeEquals(500);
    $this->assertSame('{"message":"StageEventException: PreApply did not go well.","phase":"apply"}', $contents);
  }

  /**
   * Tests an exception during a post apply event.
   *
   * @covers ::apply
   */
  public function testPostApplyException() {
    $error = new \Exception('PostApply did not go well.');
    TestSubscriber::setException($error, PostApplyEvent::class);
    $this->doStart();
    $this->doRequire();
    $this->doApply();
    $contents = $this->drupalGet("/admin/modules/project_browser/install-post_apply/drupal/awesome_module/$this->stageId");
    $this->assertSession()->statusCodeEquals(500);
    $this->assertSame('{"message":"StageEventException: PostApply did not go well.","phase":"post apply"}', $contents);
  }

  /**
   * Tests an error during a pre destroy event.
   *
   * @covers ::destroy
   */
  public function testPreDestroyError() {
    $message = t('This is a PreDestroy error.');
    $result = ValidationResult::createError([$message]);
    TestSubscriber::setTestResult([$result], PreDestroyEvent::class);
    $this->doStart();
    $this->doRequire();
    $this->doApply();
    $this->doPostApply();
    $contents = $this->drupalGet("/admin/modules/project_browser/install-destroy/drupal/awesome_module/$this->stageId");
    $this->assertSession()->statusCodeEquals(500);
    $this->assertSame('{"message":"StageEventException: This is a PreDestroy error.\n","phase":"destroy"}', $contents);
  }

  /**
   * Tests an exception during a pre destroy event.
   *
   * @covers ::destroy
   */
  public function testPreDestroyException() {
    $error = new \Exception('PreDestroy did not go well.');
    TestSubscriber::setException($error, PreDestroyEvent::class);
    $this->doStart();
    $this->doRequire();
    $this->doApply();
    $this->doPostApply();
    $contents = $this->drupalGet("/admin/modules/project_browser/install-destroy/drupal/awesome_module/$this->stageId");
    $this->assertSession()->statusCodeEquals(500);
    $this->assertSame('{"message":"StageEventException: PreDestroy did not go well.","phase":"destroy"}', $contents);
  }

  /**
   * Tests an exception during a post destroy event.
   *
   * @covers ::destroy
   */
  public function testPostDestroyException() {
    $error = new \Exception('PostDestroy did not go well.');
    TestSubscriber::setException($error, PostDestroyEvent::class);
    $this->doStart();
    $this->doRequire();
    $this->doApply();
    $this->doPostApply();
    $contents = $this->drupalGet("/admin/modules/project_browser/install-destroy/drupal/awesome_module/$this->stageId");
    $this->assertSession()->statusCodeEquals(500);
    $this->assertSame('{"message":"StageEventException: PostDestroy did not go well.","phase":"destroy"}', $contents);
  }

  /**
   * Confirms the various versions of the "install in progress" messages.
   *
   * @covers ::unlock
   */
  public function testInstallUnlockMessage() {
    $this->doStart();

    // Check for mid install unlock offer message.
    $this->drupalGet('admin/modules/project_browser/install-begin/drupal/awesome_module');
    $this->assertSession()->statusCodeEquals(418);
    $this->assertMatchesRegularExpression('/{"message":"The install staging area was locked less than 1 minutes ago. This is recent enough that a legitimate installation may be in progress. Consider waiting before unlocking the installation staging area.","unlock_url":".*admin..modules..project_browser..install..unlock\?token=[a-zA-Z0-9_-]*"}/', $this->getSession()->getPage()->getContent());
    $this->assertInstallInProgress('awesome_module', 'creating install stage');
    $this->assertFalse($this->installer->isAvailable());
    $this->assertFalse($this->installer->isApplying());
    TestTime::setFakeTimeByOffset("+800 seconds");
    $this->drupalGet('admin/modules/project_browser/install-begin/drupal/awesome_module');
    $this->assertSession()->statusCodeEquals(418);
    $this->assertFalse($this->installer->isAvailable());
    $this->assertFalse($this->installer->isApplying());
    $this->assertMatchesRegularExpression('/{"message":"The install staging area was locked 13 minutes ago.","unlock_url":".*admin..modules..project_browser..install..unlock\?token=[a-zA-Z0-9_-]*"}/', $this->getSession()->getPage()->getContent());
    $this->doRequire();
    $this->drupalGet('admin/modules/project_browser/install-begin/drupal/awesome_module');
    $this->assertSession()->statusCodeEquals(418);
    $this->assertFalse($this->installer->isAvailable());
    $this->assertFalse($this->installer->isApplying());
    $this->doApply();
    TestTime::setFakeTimeByOffset('+800 seconds');
    $this->drupalGet('admin/modules/project_browser/install-begin/drupal/awesome_module');
    $this->assertSession()->statusCodeEquals(418);
    $this->assertFalse($this->installer->isAvailable());
    $this->assertTrue($this->installer->isApplying());
    $this->assertMatchesRegularExpression('/{"message":"The install staging area was locked 13 minutes ago. It should not be unlocked as the changes from staging are being applied to the site.","unlock_url":""}/', $this->getSession()->getPage()->getContent());
    TestTime::setFakeTimeByOffset("+55 minutes");
    $this->drupalGet('admin/modules/project_browser/install-begin/drupal/awesome_module');
    $this->assertSession()->statusCodeEquals(418);
    $this->assertMatchesRegularExpression('/{"message":"The install staging area was locked 55 minutes ago. It should not be unlocked as the changes from staging are being applied to the site.","unlock_url":""}/', $this->getSession()->getPage()->getContent());
    // Unlocking the stage becomes possible after 1 hour regardless of source.
    TestTime::setFakeTimeByOffset("+75 minutes");
    $this->drupalGet('admin/modules/project_browser/install-begin/drupal/awesome_module');
    $this->assertSession()->statusCodeEquals(418);
    $this->assertMatchesRegularExpression('/{"message":"The install staging area was locked 1 hours, 15 minutes ago.","unlock_url":".*admin..modules..project_browser..install..unlock\?token=[a-zA-Z0-9_-]*"}/', $this->getSession()->getPage()->getContent());
  }

  /**
   * Confirms the break lock link is available and works.
   *
   * The break lock link is not available once the stage is applying.
   *
   * @covers ::unlock
   */
  public function testCanBreakLock() {
    $this->doStart();
    // Try beginning another install while one is in progress, but not yet in
    // the applying stage.
    $content = $this->drupalGet('admin/modules/project_browser/install-begin/drupal/metatag');
    $this->assertSession()->statusCodeEquals(418);
    $this->assertFalse($this->installer->isAvailable());
    $this->assertFalse($this->installer->isApplying());
    $json = Json::decode($content);
    $this->assertSame('The install staging area was locked less than 1 minutes ago. This is recent enough that a legitimate installation may be in progress. Consider waiting before unlocking the installation staging area.', $json['message']);
    $path = explode('?', $json['unlock_url'])[0];
    $token = explode('=', $json['unlock_url'])[1];
    $unlock_content = $this->drupalGet(substr($path, 1), ['query' => ['token' => $token]]);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertTrue($this->installer->isAvailable());
    $this->assertStringContainsString('Install staging area unlocked', $unlock_content);
    $this->assertTrue($this->installer->isAvailable());
    $this->assertFalse($this->installer->isApplying());
  }

  /**
   * Confirms stage can be unlocked despite a missing Project Browser lock.
   *
   * @covers ::unlock
   */
  public function testCanBreakStageWithMissingProjectBrowserLock() {
    $this->doStart();
    $this->sharedTempStore->get('project_browser')->delete('requiring');
    $content = $this->drupalGet('admin/modules/project_browser/install-begin/drupal/metatag');
    $this->assertSession()->statusCodeEquals(418);
    $this->assertFalse($this->installer->isAvailable());
    $this->assertFalse($this->installer->isApplying());
    $json = Json::decode($content);
    $this->assertSame('An install staging area claimed by Project Browser exists but has expired. You may unlock the stage and try the install again.', $json['message']);
    $path = explode('?', $json['unlock_url'])[0];
    $token = explode('=', $json['unlock_url'])[1];
    $unlock_content = $this->drupalGet(substr($path, 1), ['query' => ['token' => $token]]);
    $this->assertSession()->statusCodeEquals(200);
    $this->assertTrue($this->installer->isAvailable());
    $this->assertStringContainsString('Install staging area unlocked', $unlock_content);
    $this->assertTrue($this->installer->isAvailable());
    $this->assertFalse($this->installer->isApplying());
  }

  /**
   * Confirm a module and its dependencies can be installed via the endpoint.
   *
   * @covers ::activateModule
   */
  public function testCoreModuleActivate() {
    $this->drupalGet('admin/modules');
    $views_checkbox = $this->getSession()->getPage()->find('css', '#edit-modules-views-enable');
    $views_ui_checkbox = $this->getSession()->getPage()->find('css', '#edit-modules-views-ui-enable');
    $this->assertFalse($views_checkbox->isChecked());
    $this->assertFalse($views_ui_checkbox->isChecked());

    $content = $this->drupalGet('admin/modules/project_browser/activate-module/views_ui');
    $this->assertSame('{"status":0}', $content);
    $this->rebuildContainer();
    $this->drupalGet('admin/modules');
    $views_checkbox = $this->getSession()->getPage()->find('css', '#edit-modules-views-enable');
    $views_ui_checkbox = $this->getSession()->getPage()->find('css', '#edit-modules-views-ui-enable');
    $this->assertTrue($views_checkbox->isChecked());
    $this->assertTrue($views_ui_checkbox->isChecked());
  }

  /**
   * Asserts that a module install is not in progress.
   *
   * @param string $module
   *   The module machine name.
   */
  protected function assertInstallNotInProgress($module) {
    $this->assertProjectBrowserTempStatus(NULL, NULL);
    $this->drupalGet("/admin/modules/project_browser/install_in_progress/$module");
    $this->assertSame('{"status":0}', $this->getSession()->getPage()->getContent());
    $this->drupalGet('/admin/modules/project_browser/install_in_progress/metatag');
    $this->assertSame('{"status":0}', $this->getSession()->getPage()->getContent());
  }

  /**
   * Confirms the project browser in progress input provides the expected value.
   *
   * @param string $module
   *   The module being enabled.
   * @param string $phase
   *   The install phase.
   */
  protected function assertInstallInProgress($module, $phase = NULL) {
    $expect_install = ['project_id' => $module];
    if (!is_null($phase)) {
      $expect_install['phase'] = $phase;
    }
    if (!empty($this->stageId)) {
      $expect_install['stage_id'] = $this->stageId;
    }
    $this->assertProjectBrowserTempStatus($expect_install, NULL);
    $this->drupalGet("/admin/modules/project_browser/install_in_progress/$module");
    $this->assertSame(sprintf('{"status":1,"phase":"%s"}', $phase), $this->getSession()->getPage()->getContent());
    $this->drupalGet('/admin/modules/project_browser/install_in_progress/metatag');
    $this->assertSame('{"status":0}', $this->getSession()->getPage()->getContent());
  }

  /**
   * Confirms the tempstore install status are as expected.
   *
   * @param array $expected_requiring
   *   The expected value of the 'requiring' state.
   * @param string $expected_installing
   *   The expected value of the 'core requiring' state.
   */
  protected function assertProjectBrowserTempStatus($expected_requiring, $expected_installing) {
    $project_browser_requiring = $this->sharedTempStore->get('project_browser')->get('requiring');
    $project_browser_installing = $this->sharedTempStore->get('project_browser')->get('installing');
    $this->assertSame($expected_requiring, $project_browser_requiring);
    $this->assertSame($expected_installing, $project_browser_installing);
  }

}
