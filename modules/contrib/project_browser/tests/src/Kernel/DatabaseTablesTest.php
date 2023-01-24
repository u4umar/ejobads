<?php

namespace Drupal\Tests\project_browser\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * @covers \project_browser_schema
 *
 * @group project_browser
 */
class DatabaseTablesTest extends KernelTestBase {

  /**
   * Tests that Project Browser's DB tables are created and destroyed correctly.
   */
  public function testDatabaseSchemaCreationAndDestruction(): void {
    /** @var \Drupal\Core\Extension\ModuleInstallerInterface $module_installer */
    $module_installer = $this->container->get('module_installer');
    /** @var \Drupal\Core\Database\Schema $database */
    $schema = $this->container->get('database')->schema();
    /** @var \Drupal\Core\Database\Connection $database */
    $database = $this->container->get('database');

    $module_installer->install(['project_browser']);
    $this->assertTrue($schema->tableExists('project_browser_projects'));
    $this->assertTrue($schema->tableExists('project_browser_categories'));

    // Make sure the fixture files do have data in them.
    $rows = $database->select('project_browser_projects')->countQuery()->execute()->fetchCol();
    $this->assertGreaterThan(1, $rows[0]);
    $rows = $database->select('project_browser_categories')->countQuery()->execute()->fetchCol();
    $this->assertGreaterThan(1, $rows[0]);

    $module_installer->uninstall(['project_browser']);
    $this->assertFalse($schema->tableExists('project_browser_projects'));
    $this->assertFalse($schema->tableExists('project_browser_categories'));
  }

}
