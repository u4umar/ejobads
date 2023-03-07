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

  /**
   * The installer.
   *
   * @var \Drupal\project_browser\ComposerInstaller\Installer
   */
  private $installer;

  /**
   * The event dispatcher service.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * InstallReadiness constructor.
   *
   * @param \Drupal\project_browser\ComposerInstaller\Installer $installer
   *   The installer.
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   *   The event dispatcher.
   */
  public function __construct(Installer $installer, EventDispatcherInterface $event_dispatcher) {
    $this->installer = $installer;
    $this->eventDispatcher = $event_dispatcher;
  }

  /**
   * Checks if the environment meets Package Manager install requirements.
   *
   * @return false|string
   *   FALSE if no validation errors, otherwise an error message.
   */
  public function validatePackageManager() {
    $text = '';
    $results = $this->runStatusCheck($this->installer, $this->eventDispatcher, TRUE);
    foreach ($results as $result) {
      $messages = $result->getMessages();
      $summary = $result->getSummary();

      if ($summary && $summary !== $messages) {
        array_unshift($messages, $summary);
      }
      $text .= implode("\n", $messages) . "\n";
    }
    return !empty($text) ? $text : FALSE;
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
