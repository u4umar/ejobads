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

  public function __construct(
    private readonly ProjectBrowserFixtureHelper $fixtureHelper,
  ) {}

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
