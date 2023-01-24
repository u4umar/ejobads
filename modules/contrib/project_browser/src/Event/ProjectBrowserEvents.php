<?php

namespace Drupal\project_browser\Event;

/**
 * Contains all dispatched events for Project Browser.
 */
final class ProjectBrowserEvents {

  /**
   * The name of the event triggered before mockapi fixture is updated.
   *
   * This event allows modules to react to update:project-modules drush command.
   *
   * @Event
   *
   * @var string
   */
  const UPDATE_FIXTURE = 'project_browser.update_fixture';

}
