<?php

namespace Drupal\project_browser\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\package_manager\StatusCheckTrait;
use Drupal\project_browser\ComposerInstaller\Installer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Defines a controller for verifying install readiness.
 *
 * @internal
 *   Controller classes are internal.
 */
class InstallReadinessController extends ControllerBase {

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
   * Constructs an InstallReadinessController object.
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
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('project_browser.installer'),
      $container->get('event_dispatcher'),
    );
  }

  /**
   * Checks environment and stage for install readiness.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The response.
   */
  public function checkReadiness() {
    // If the installer is not available, the fact that it is claimed implies
    // the validators pass, so we can set 'pm_validation' to FALSE, knowing that
    // an install attempt will include the same validation present in a status
    // check thus providing the same protection.
    if (!$this->installer->isAvailable()) {
      return new JsonResponse([
        'pm_validation' => FALSE,
        'stage_available' => $this->installer->isAvailable(),
      ]);
    }

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

    return new JsonResponse([
      'pm_validation' => !empty($text) ? $text : FALSE,
      'stage_available' => $this->installer->isAvailable(),
    ]);
  }

}
