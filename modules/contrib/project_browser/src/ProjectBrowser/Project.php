<?php

namespace Drupal\project_browser\ProjectBrowser;

use Drupal\Component\Utility\Html;
use Drupal\Component\Utility\Unicode;

/**
 * Defines a single Project.
 */
class Project implements \JsonSerializable {

  /**
   * Logo of the project.
   *
   * @var array
   */
  private array $logo;

  /**
   * Whether the project is compatible with the current version of Drupal.
   *
   * @var bool
   */
  private bool $isCompatible;

  /**
   * Whether the project is considered to be maintained or not.
   *
   * @var bool
   */
  private bool $isMaintained;

  /**
   * Whether the project is considered to be covered or not.
   *
   * @var bool
   */
  private bool $isCovered;

  /**
   * Whether the project is considered to be active or not.
   *
   * @var bool
   */
  private bool $isActive;

  /**
   * User start count of the project.
   *
   * @var int
   */
  private int $starUserCount;

  /**
   * Total usage of the project.
   *
   * @var int
   */
  private int $projectUsageTotal;

  /**
   * URL of the project.
   *
   * @var string
   */
  private string $url = '';

  /**
   * Value of module_categories of the project.
   *
   * @var array
   */
  private array $categories = [];

  /**
   * Value of project_machine_name of the project.
   *
   * @var string
   */
  private string $machineName;

  /**
   * Images of the project.
   *
   * @var array
   */
  private array $images = [];

  /**
   * Body field of the project in array format.
   *
   * @var array
   */
  private array $body;

  /**
   * ID of the project.
   *
   * @var string
   */
  private string $id;

  /**
   * Title of the project.
   *
   * @var string
   */
  private string $title;

  /**
   * Status of the project.
   *
   * @var int
   */
  private int $status;

  /**
   * When was the project changed last timestamp.
   *
   * @var int
   */
  private int $changed;

  /**
   * When was the project created last timestamp.
   *
   * @var int
   */
  private int $created;

  /**
   * Author of the project in array format.
   *
   * @var array
   */
  private array $author;

  /**
   * Warnings for the project.
   *
   * @var array
   */
  private array $warnings = [];

  /**
   * Composer namespace of the project.
   *
   * @var string
   */
  private string $composerNamespace;

  /**
   * Set the author of Project.
   *
   * @param array $author
   *   Author in array format.
   *
   * @return $this
   */
  public function setAuthor(array $author) {
    $this->author = $author;
    return $this;
  }

  /**
   * Set the project created timestamp.
   *
   * @param int $created
   *   Timestamp.
   *
   * @return $this
   */
  public function setCreatedTimestamp(int $created) {
    $this->created = $created;
    return $this;
  }

  /**
   * Set the project changed timestamp.
   *
   * @param int $changed
   *   Timestamp.
   *
   * @return $this
   */
  public function setChangedTimestamp(int $changed) {
    $this->changed = $changed;
    return $this;
  }

  /**
   * Set the project status.
   *
   * @param int $status
   *   Status of the project.
   *
   * @return $this
   */
  public function setProjectStatus(int $status) {
    $this->status = $status;
    return $this;
  }

  /**
   * Set the project title.
   *
   * @param string $title
   *   Title of the project.
   *
   * @return $this
   */
  public function setProjectTitle(string $title) {
    $this->title = $title;
    return $this;
  }

  /**
   * Set the unique identifier of Project, eg nid.
   *
   * @param string $id
   *   ID of the project.
   *
   * @return $this
   */
  public function setId(string $id) {
    $this->id = $id;
    return $this;
  }

  /**
   * Set the project short description.
   *
   * @param array $body
   *   Body in array format.
   *
   * @return $this
   */
  public function setSummary(array $body) {
    $this->body = $body;
    if (empty($this->body['summary'])) {
      $this->body['summary'] = $this->body['value'] ?? '';
    }
    $this->body['summary'] = Html::escape(strip_tags($this->body['summary']));
    $this->body['summary'] = Unicode::truncate($this->body['summary'], 200, TRUE, TRUE);
    return $this;
  }

  /**
   * Set the images associated with the project.
   *
   * @param array $images
   *   Images in array format.
   *
   * @return $this
   */
  public function setImages(array $images) {
    $this->images = $images;
    return $this;
  }

  /**
   * Set the project machine name.
   *
   * @param string $machine_name
   *   Machine name of the module.
   *
   * @return $this
   */
  public function setMachineName(string $machine_name) {
    $this->machineName = $machine_name;
    return $this;
  }

  /**
   * Set the project logo.
   *
   * @param array $logo
   *   Logo in array format.
   *
   * @return $this
   */
  public function setLogo(array $logo) {
    $this->logo = $logo;
    return $this;
  }

  /**
   * Set the composer namespace.
   *
   * @param string $composer_namespace
   *   Composer namespace of the module.
   *
   * @return $this
   */
  public function setComposerNamespace(string $composer_namespace) {
    $this->composerNamespace = $composer_namespace;
    return $this;
  }

  /**
   * Set the categories this project belongs.
   *
   * @param array $categories
   *   Module category ids.
   *
   * @return $this
   */
  public function setModuleCategories(array $categories) {
    $this->categories = $categories;
    return $this;
  }

  /**
   * Set the URL to the project page, where someone could learn more about this project.
   *
   * @param string $url
   *   The URL.
   *
   * @return $this
   */
  public function setProjectUrl(string $url) {
    $this->url = $url;
    return $this;
  }

  /**
   * Set the total usage count of all releases.
   *
   * @param int $usage_total
   *   Total usage.
   *
   * @return $this
   */
  public function setProjectUsageTotal(int $usage_total) {
    $this->projectUsageTotal = $usage_total;
    return $this;
  }

  /**
   * Set the project star user count.
   *
   * @param int $star_user_count
   *   Start user count value.
   *
   * @return $this
   */
  public function setProjectStarUserCount(int $star_user_count) {
    $this->starUserCount = $star_user_count;
    return $this;
  }

  /**
   * Set if the project is considered active or not.
   *
   * @param bool $is_active
   *   Value to set.
   *
   * @return $this
   */
  public function setIsActive(bool $is_active) {
    $this->isActive = $is_active;
    return $this;
  }

  /**
   * Set if the project is considered covered or not.
   *
   * @param bool $is_covered
   *   Value to set.
   *
   * @return $this
   */
  public function setIsCovered(bool $is_covered) {
    $this->isCovered = $is_covered;
    return $this;
  }

  /**
   * Set if the project is considered maintained or not.
   *
   * @param bool $is_maintained
   *   Value to set.
   *
   * @return $this
   */
  public function setIsMaintained(bool $is_maintained) {
    $this->isMaintained = $is_maintained;
    return $this;
  }

  /**
   * Set whether the project is compatible with the current Drupal installation.
   *
   * @param bool $compatible
   *   Whether the project is compatible or not.
   *
   * @return $this
   */
  public function setIsCompatible(bool $compatible) {
    $this->isCompatible = $compatible;
    return $this;
  }

  /**
   * Warnings related to installing a given module.
   *
   * @param string[] $warnings
   *   Warnings about the module to present the the user.
   *
   * @return $this
   */
  public function setWarnings(array $warnings) {
    $this->warnings = $warnings;
    return $this;
  }

  /**
   * Returns the machine name of the project.
   *
   * @return string
   *   The machine name of the project.
   */
  public function getMachineName(): string {
    return $this->machineName;
  }

  /**
   * Returns the categories of the project.
   *
   * @return array
   *   The categories of the project.
   */
  public function getModuleCategories(): array {
    return $this->categories;
  }

  /**
   * Returns the title of the project.
   *
   * @return string
   *   The title of the project.
   */
  public function getTitle(): string {
    return $this->title;
  }

  /**
   * Returns the selector id of the project.
   *
   * @return string
   *   Selector id of the project.
   */
  public function getSelectorId(): string {
    return str_replace('_', '-', $this->machineName);
  }

  /**
   * Returns whether the project is considered covered or not.
   *
   * @return bool
   *   Covered status.
   */
  public function isCovered(): bool {
    return $this->isCovered;
  }

  /**
   * {@inheritdoc}
   */
  #[\ReturnTypeWillChange]
  public function jsonSerialize() {
    return (object) [
      'is_compatible' => $this->isCompatible,
      'is_covered' => $this->isCovered(),
      'project_usage_total' => $this->projectUsageTotal,
      'module_categories' => $this->getModuleCategories(),
      'project_machine_name' => $this->getMachineName(),
      'project_images' => $this->images,
      'logo' => $this->logo,
      'body' => $this->body,
      'title' => $this->getTitle(),
      'author' => $this->author,
      'warnings' => $this->warnings,
      'composer_namespace' => $this->composerNamespace,
      // @todo Not used in Svelte. Audit in https://www.drupal.org/i/3309273.
      'is_maintained' => $this->isMaintained,
      'is_active' => $this->isActive,
      'flag_project_star_user_count' => $this->starUserCount,
      'url' => $this->url,
      'id' => $this->id,
      'status' => $this->status,
      'changed' => $this->changed,
      'created' => $this->created,
      'selector_id' => $this->getSelectorId(),
    ];
  }

}
