<?php

namespace Drupal\project_browser\EventSubscriber;

use Drupal\project_browser\Event\ProjectBrowserEvents;
use Drupal\project_browser\Event\UpdateFixtureEvent;
use Drupal\project_browser\ProjectBrowserFixtureHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Update Fixture event subscriber.
 */
class UpdateFixtureSubscriber implements EventSubscriberInterface {

  /**
   * @var \Drupal\project_browser\ProjectBrowserFixtureHelper
   */
  protected ProjectBrowserFixtureHelper $fixtureHelper;

  public function __construct(ProjectBrowserFixtureHelper $fixture_helper) {
    $this->fixtureHelper = $fixture_helper;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      ProjectBrowserEvents::UPDATE_FIXTURE => 'onFixtureUpdate',
    ];
  }

  /**
   * Update fixture only if plugin id is 'drupalorg_mockapi'.
   *
   * @param \Drupal\project_browser\Event\UpdateFixtureEvent $event
   *   The event.
   */
  public function onFixtureUpdate(UpdateFixtureEvent $event) {
    $current_sources = $event->enabledSource->getCurrentSources();
    if (!empty($current_sources['drupalorg_mockapi'])) {
      $this->fixtureHelper->updateMostRecentChanges();
    }
  }

}
