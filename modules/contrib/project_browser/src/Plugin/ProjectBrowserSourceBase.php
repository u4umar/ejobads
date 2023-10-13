<?php

namespace Drupal\project_browser\Plugin;

use Drupal\Core\Plugin\PluginBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Defines an abstract base class for a Project Browser source.
 *
 * @see \Drupal\project_browser\Annotation\ProjectBrowserSource
 * @see \Drupal\project_browser\Plugin\ProjectBrowserSourceManager
 * @see plugin_api
 */
abstract class ProjectBrowserSourceBase extends PluginBase implements ProjectBrowserSourceInterface {

  use StringTranslationTrait;

  /**
   * Label for maintained status.
   *
   * @var string
   */
  const MAINTAINED_LABEL = 'Maintained';

  /**
   * ID for maintained status.
   *
   * @var string
   */
  const MAINTAINED_ID = 'maintained';

  /**
   * Label for covered status.
   *
   * @var string
   */
  const COVERED_LABEL = 'Covered by a security policy';

  /**
   * ID for covered status.
   *
   * @var string
   */
  const COVERED_ID = 'covered';

  /**
   * Label for active status.
   *
   * @var string
   */
  const ACTIVE_LABEL = 'Active';

  /**
   * ID for active status.
   *
   * @var string
   */
  const ACTIVE_ID = 'active';

  /**
   * Label for all values.
   *
   * @var string
   */
  const ALL_VALUES_LABEL = 'Show all';

  /**
   * ID for all values.
   *
   * @var string
   */
  const ALL_VALUES_ID = 'all';

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * Returns the available security options that plugins will parse.
   *
   * @return array
   *   Options offered.
   */
  final public function getSecurityOptions(): array {
    return [
      self::COVERED_ID => $this->t('@covered_label', ['@covered_label' => self::COVERED_LABEL]),
      self::ALL_VALUES_ID => $this->t('@all_values_label', ['@all_values_label' => self::ALL_VALUES_LABEL]),
    ];
  }

  /**
   * Returns the available maintenance options that plugins will parse.
   *
   * @return array
   *   Options offered.
   */
  final public function getMaintenanceOptions(): array {
    return [
      self::MAINTAINED_ID => $this->t('@maintained_label', ['@maintained_label' => self::MAINTAINED_LABEL]),
      self::ALL_VALUES_ID => $this->t('@all_values_label', ['@all_values_label' => self::ALL_VALUES_LABEL]),
    ];
  }

  /**
   * Returns the available maintenance options that plugins will parse.
   *
   * @return array
   *   Options offered.
   */
  final public function getDevelopmentOptions(): array {
    return [
      self::ACTIVE_ID => $this->t('@active_label', ['@active_label' => self::ACTIVE_LABEL]),
      self::ALL_VALUES_ID => $this->t('@all_values_label', ['@all_values_label' => self::ALL_VALUES_LABEL]),
    ];
  }

  /**
   * Returns the available sort options that plugins will parse.
   *
   * @return array
   *   Options offered.
   */
  public function getSortOptions(): array {
    return [
      'usage_total' => [
        'id' => 'usage_total',
        'text' => $this->t('Most Popular'),
      ],
      'a_z' => [
        'id' => 'a_z',
        'text' => $this->t('A-Z'),
      ],
      'z_a' => [
        'id' => 'z_a',
        'text' => $this->t('Z-A'),
      ],
      'created' => [
        'id' => 'created',
        'text' => $this->t('Newest First'),
      ],
      'best_match' => [
        'id' => 'best_match',
        'text' => $this->t('Most Relevant'),
      ],
    ];
  }

}
