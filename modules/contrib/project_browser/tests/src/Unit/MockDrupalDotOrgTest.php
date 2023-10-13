<?php

namespace Drupal\Tests\project_browser\Unit;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Database\Connection;
use Drupal\project_browser\Plugin\ProjectBrowserSource\MockDrupalDotOrg;
use Drupal\Tests\UnitTestCase;
use GuzzleHttp\ClientInterface;
use Psr\Log\LoggerInterface;

/**
 * Tests plugin functions.
 *
 * @group project_browser
 */
class MockDrupalDotOrgTest extends UnitTestCase {

  /**
   * The plugin.
   *
   * @var \Drupal\project_browser\Plugin\ProjectBrowserSource\MockDrupalDotOrg
   */
  protected $plugin;

  /**
   * A logger instance.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * The state object.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * ProjectBrowser cache bin.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cacheBin;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->logger = $this->createMock(LoggerInterface::class);
    $this->database = $this->createMock(Connection::class);
    $this->httpClient = $this->createMock(ClientInterface::class);
    $this->cacheBin = $this->createMock(CacheBackendInterface::class);
    $this->state = $this->createMock('\Drupal\Core\State\StateInterface');

    $configuration = [];
    $plugin_id = $this->randomMachineName();
    $plugin_definition = [];
    $this->plugin = new MockDrupalDotOrg($configuration, $plugin_id, $plugin_definition, $this->logger, $this->database, $this->httpClient, $this->state, $this->cacheBin);
  }

  /**
   * Gets a protected/private method to test.
   *
   * @param string $name
   *   The method name.
   *
   * @return \ReflectionMethod
   *   The accessible method.
   */
  protected static function getMethod($name) {
    $class = new \ReflectionClass(MockDrupalDotOrg::class);
    $method = $class->getMethod($name);
    $method->setAccessible(TRUE);
    return $method;
  }

  /**
   * Tests relative to absolute URL conversion.
   */
  public function testRelativeToAbsoluteUrl() {
    // Project body with relative URLs.
    $project_data['body'] = ['value' => '<img src="/files/issues/123" alt="Image1" /><img src="/files/issues/321" alt="Image2" />'];
    // Expected Absolute URLs.
    $expected = ['value' => '<img src="https://www.drupal.org/files/issues/123" alt="Image1" /><img src="https://www.drupal.org/files/issues/321" alt="Image2" />'];
    $method = self::getMethod('relativeToAbsoluteUrls');
    $after_conversion = $method->invokeArgs($this->plugin, [$project_data['body'], 'https://www.drupal.org']);
    $this->assertSame($expected, $after_conversion);
  }

}
