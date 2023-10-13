<?php

namespace Drupal\project_browser;

use Drupal\package_manager\StatusCheckTrait;
use Drupal\project_browser\ComposerInstaller\Installer;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Defines Installer service.
 */
class InstallReadiness {

  use StatusCheckTrait;

  public function __construct(
    private readonly Installer $installer,
    private readonly EventDispatcherInterface $eventDispatcher,
  ) {}

  /**
   * Checks if the environment meets Package Manager install requirements.
   *
   * @return false|string
   *   FALSE if no validation errors, otherwise an error message.
   */
  public function validatePackageManager() {
    $text = '';
    $results = $this->runStatusCheck($this->installer, $this->eventDispatcher);
    foreach ($results as $result) {
      $messages = $result->messages;
      $summary = $result->summary;

      if ($summary) {
        array_unshift($messages, $summary);
      }
      $text .= implode("\n", $messages) . "\n";
    }
    return $text ?: FALSE;
  }

  /**
   * Checks if the installer is available.
   *
   * @return bool
   *   If the installer is currently available.
   */
  public function installerAvailable() {
    return $this->installer->isAvailable();
  }

}
