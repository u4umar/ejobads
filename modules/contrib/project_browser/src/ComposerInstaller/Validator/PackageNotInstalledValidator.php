<?php

namespace Drupal\project_browser\ComposerInstaller\Validator;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\package_manager\Event\PreRequireEvent;
use Drupal\project_browser\ComposerInstaller\Installer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Validates that packages to be installed are not already installed.
 *
 * @internal
 *   Tagged services are internal.
 */
final class PackageNotInstalledValidator implements EventSubscriberInterface {

  use StringTranslationTrait;

  /**
   * Constructs a PackageNotInstalledValidator object.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $translation
   *   The translation service.
   */
  public function __construct(TranslationInterface $translation) {
    $this->setStringTranslation($translation);
  }

  /**
   * Validates that packages are not already installed with composer.
   *
   * @param \Drupal\package_manager\Event\PreRequireEvent $event
   *   The event object.
   */
  public function validatePackagesNotAlreadyInstalled(PreRequireEvent $event): void {
    $stage = $event->getStage();
    if (!$stage instanceof Installer) {
      return;
    }

    $installed_packages = $stage->getActiveComposer()->getInstalledPackages();
    // Assuming project browser cannot install dev releases, since we are not
    // calling $event->getDevPackages() for now.
    $required_packages = $event->getRuntimePackages();
    $already_installed_packages = [];

    foreach (array_keys($required_packages) as $required_package) {
      if (array_key_exists($required_package, $installed_packages)) {
        $already_installed_packages[] = $required_package;
      }
    }

    if (!empty($already_installed_packages)) {
      $summary = $this->formatPlural(count($already_installed_packages), 'The following package is already installed:', 'The following packages are already installed:');
      $event->addError($already_installed_packages, $summary);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      PreRequireEvent::class => 'validatePackagesNotAlreadyInstalled',
    ];
  }

}
