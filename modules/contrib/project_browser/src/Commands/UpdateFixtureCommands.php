<?php

namespace Drupal\project_browser\Commands;

// cspell:ignore commandfile

use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\project_browser\EnabledSourceHandler;
use Drupal\project_browser\Event\ProjectBrowserEvents;
use Drupal\project_browser\Event\UpdateFixtureEvent;
use Drupal\project_browser\ProjectBrowserFixtureHelper;
use Drush\Commands\DrushCommands;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 *
 * See these files for an example of injecting Drupal services:
 *   - http://cgit.drupalcode.org/devel/tree/src/Commands/DevelCommands.php
 *   - http://cgit.drupalcode.org/devel/tree/drush.services.yml
 */
class UpdateFixtureCommands extends DrushCommands {

  use StringTranslationTrait;

  public function __construct(
    private readonly LoggerChannelFactoryInterface $loggerChannelFactory,
    private readonly EnabledSourceHandler $enabledSource,
    private readonly EventDispatcherInterface $eventDispatcher,
    private readonly ProjectBrowserFixtureHelper $fixtureHelper,
  ) {
    parent::__construct();
  }

  /**
   * Update to latest modules since fixture created.
   *
   * @command update:project-modules
   * @aliases update-modules
   *
   * @usage update:project-modules
   */
  public function updateProjectModules() {
    // Log the start of the script.
    $this->loggerChannelFactory->get('project_browser')->info($this->t('Update fixture batch operations start'));
    $this->logger()->notice($this->t('Starting...'));

    // Dispatch the event so that event listeners of other source can update their fixture.
    $event = new UpdateFixtureEvent($this->enabledSource);
    $this->eventDispatcher->dispatch($event, ProjectBrowserEvents::UPDATE_FIXTURE);

    $this->logger()->notice($this->t('Completed.'));
  }

  /**
   * Generate new fixtures.
   *
   * @command update:generate-fixture
   * @aliases pb-fixture
   *
   * @usage update:generate-fixture
   */
  public function generateFixture() {
    $current_sources = $this->enabledSource->getCurrentSources();
    if (!empty($current_sources['drupalorg_mockapi'])) {
      $this->logger()->notice($this->t('Begin Fixture Generation'));
      $sandbox = [];

      $module_path = \Drupal::service('module_handler')->getModule('project_browser')->getPath();
      while (empty($sandbox) || $sandbox['#finished'] !== TRUE) {
        $progress_message = $this->fixtureHelper->hackyFixtureMaker($sandbox);

        $this->logger()->notice($progress_message);

        if ($sandbox['#finished'] === TRUE || $progress_message === 'Fixture generation complete') {
          $this->fixtureHelper->populateFromFixture($module_path . '/fixtures/project_data.json', $module_path . '/fixtures/categories.json');
          $this->logger()->notice($this->t('Writing fixture to database'));
          break;
        }
      }
      $this->logger()->notice($this->t('Updating project_browser.install'));
      $install_file_contents = file_get_contents($module_path . '/project_browser.install');
      preg_match_all('/project_browser_update_(\d+)\(\)/', $install_file_contents, $matches);
      $update_hooks = $matches[1];
      sort($update_hooks);
      $new_hook_id = end($update_hooks) + 1;
      $update_hook = <<<'UPDATE'

function project_browser_update_{$new_hook_id}() {
  _project_browser_populate_from_fixture();
}

UPDATE;
      // Add update hook to the install file.
      file_put_contents($module_path . '/project_browser.install', $install_file_contents . str_replace('{$new_hook_id}', $new_hook_id, $update_hook));
    }
    else {
      $this->logger()->notice($this->t('Drupal.org mockapi is not enabled for fixture generation'));
    }
  }

}
