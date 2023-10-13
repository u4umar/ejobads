<?php

namespace Drupal\project_browser\Event;

use Drupal\project_browser\EnabledSourceHandler;
use Symfony\Contracts\EventDispatcher\Event;

class UpdateFixtureEvent extends Event {

  public function __construct(
    public EnabledSourceHandler $enabledSource,
  ) {}

}
