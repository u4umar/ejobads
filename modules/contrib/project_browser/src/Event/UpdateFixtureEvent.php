<?php

namespace Drupal\project_browser\Event;

use Drupal\project_browser\EnabledSourceHandler;
use Symfony\Contracts\EventDispatcher\Event;

class UpdateFixtureEvent extends Event {

  /**
   * The EnabledSourceHandler.
   *
   * @var \Drupal\project_browser\EnabledSourceHandler
   */
  public $enabledSource;

  /**
   * Constructs the object.
   *
   * @param \Drupal\project_browser\EnabledSourceHandler $enabled_source
   *   The enabled source.
   */
  public function __construct(EnabledSourceHandler $enabled_source) {
    $this->enabledSource = $enabled_source;
  }

}
