<?php

namespace Drupal\project_browser\ComposerInstaller\Validator;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\package_manager\Event\PreApplyEvent;
use Drupal\project_browser\ComposerInstaller\Installer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Validates that Drupal core was not updated during project install.
 *
 * @internal
 *   Tagged services are internal.
 */
final class CoreNotUpdatedValidator implements EventSubscriberInterface {

  use StringTranslationTrait;

  /**
   * Constructs a CoreNotUpdatedValidator object.
   *
   * @param \Drupal\Core\StringTranslation\TranslationInterface $translation
   *   The translation service.
   */
  public function __construct(TranslationInterface $translation) {
    $this->setStringTranslation($translation);
  }

  /**
   * Validates Drupal core was not updated during project install.
   *
   * @param \Drupal\package_manager\Event\PreApplyEvent $event
   *   The event object.
   */
  public function validateCoreNotUpdated(PreApplyEvent $event): void {
    $stage = $event->getStage();
    if (!$stage instanceof Installer) {
      return;
    }

    $staged = $stage->getStageComposer();
    $active = $stage->getActiveComposer();
    $updated_packages = $staged->getPackagesWithDifferentVersionsIn($active);

    if (array_key_exists('drupal/core', $updated_packages)) {
      $event->addError([
        $this->t('Drupal core has been updated in the staging area, which is not allowed by Project Browser.'),
      ]);
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      PreApplyEvent::class => 'validateCoreNotUpdated',
    ];
  }

}
