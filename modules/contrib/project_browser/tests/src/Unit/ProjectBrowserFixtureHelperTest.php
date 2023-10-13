<?php

namespace Drupal\Tests\project_browser\Unit;

// cspell:ignore elle

use Drupal\Core\Database\Connection;
use Drupal\Core\Database\Query\Insert;
use Drupal\Core\Database\Query\Truncate;
use Drupal\Core\State\StateInterface;
use Drupal\project_browser\ProjectBrowserFixtureHelper;
use Drupal\Tests\UnitTestCase;
use GuzzleHttp\ClientInterface;
use org\bovigo\vfs\vfsStream;

/**
 * @coversDefaultClass \Drupal\project_browser\ProjectBrowserFixtureHelper
 *
 * @group project_browser
 */
class ProjectBrowserFixtureHelperTest extends UnitTestCase {

  /**
   * @covers ::populateFromFixture
   */
  public function testPopulateFromFixture(): void {
    vfsStream::setup('root');
    vfsStream::create([
      'fixtures' => [
        'projects.json' => <<<'EOS'
[
  {
    "title": " 1&nbsp;Starts  With a Number .",
    "author": "Elle Woods",
    "changed": "906",
    "created": "609",
    "field_project_type": "full",
    "field_security_advisory_coverage": "not-covered",
    "flag_project_star_user_count": "10",
    "nid": 0,
    "status": "1",
    "project_data": {
      "body": {
        "summary": "<p>Text here for fake summary content.</p>"
      },
      "field_project_images": [],
      "field_project_machine_name": "1_starts_with_a_number",
      "project_usage": {
        "7.x-1.x": 103,
        "8.x-2.x": 10
      },
      "taxonomy_vocabulary_3": [],
      "taxonomy_vocabulary_44": {
        "uri": "https://www.drupal.org/api-d7/taxonomy_term/13028",
        "id": "13028",
        "resource": "taxonomy_term"
      },
      "taxonomy_vocabulary_46": {
        "uri": "https://www.drupal.org/api-d7/taxonomy_term/9988",
        "id": "9988",
        "resource": "taxonomy_term"
      },
      "type": "project_module"
    }
  }
]
EOS,
        'categories.json' => '[{"tid":"53","pid":"3311014"},{"tid":"57","pid":"3311014"}]',
      ],
    ]);

    $truncate = $this->prophesize(Truncate::class);
    $insert_projects = $this->prophesize(Insert::class);
    $insert_categories = $this->prophesize(Insert::class);
    $connection = $this->prophesize(Connection::class);
    $connection->truncate('project_browser_projects')->willReturn($truncate->reveal());
    $connection->truncate('project_browser_categories')->willReturn($truncate->reveal());
    $connection->insert('project_browser_projects')->willReturn($insert_projects->reveal());
    $connection->insert('project_browser_categories')->willReturn($insert_categories->reveal());
    $insert_projects->fields([
      'nid',
      'title',
      'author',
      'created',
      'changed',
      'project_usage_total',
      'maintenance_status',
      'development_status',
      'status',
      'field_security_advisory_coverage',
      'flag_project_star_user_count',
      'field_project_type',
      'project_data',
      'field_project_machine_name',
    ])->willReturn($insert_projects->reveal());
    $insert_projects->values([
      'title' => '1 Starts With a Number.',
      'author' => 'Elle Woods',
      'changed' => '906',
      'created' => '609',
      'field_project_type' => 'full',
      'field_security_advisory_coverage' => 'not-covered',
      'flag_project_star_user_count' => '10',
      'nid' => 0,
      'status' => '1',
      'project_data' => ['body' => ['summary' => '<p>Text here for fake summary content.</p>'], 'field_project_images' => [], 'field_project_machine_name' => '1_starts_with_a_number', 'project_usage' => ['7.x-1.x' => 103, '8.x-2.x' => 10], 'taxonomy_vocabulary_3' => [], 'taxonomy_vocabulary_44' => ['uri' => 'https://www.drupal.org/api-d7/taxonomy_term/13028', 'id' => '13028', 'resource' => 'taxonomy_term'], 'taxonomy_vocabulary_46' => ['uri' => 'https://www.drupal.org/api-d7/taxonomy_term/9988', 'id' => '9988', 'resource' => 'taxonomy_term'], 'type' => 'project_module'],
      'maintenance_status' => '13028',
      'development_status' => '9988',
      'field_project_machine_name' => '1_starts_with_a_number',
    ])->willReturn($insert_projects->reveal());
    $insert_projects->execute()->shouldBeCalled();
    $insert_categories->fields(['tid', 'pid'])->willReturn($insert_categories->reveal());
    $insert_categories->values(['tid' => '53', 'pid' => '3311014'])->willReturn($insert_categories->reveal());
    $insert_categories->values(['tid' => '57', 'pid' => '3311014'])->willReturn($insert_categories->reveal());
    $insert_categories->execute()->shouldBeCalled();

    $state = $this->prophesize(StateInterface::class);
    $state->set('project_browser.last_imported', '906')->shouldBeCalled();

    $http_client = $this->prophesize(ClientInterface::class);

    $class = new ProjectBrowserFixtureHelper($connection->reveal(), $state->reveal(), $http_client->reveal());
    $class->populateFromFixture(vfsStream::url('root/fixtures/projects.json'), vfsStream::url('root/fixtures/categories.json'));
  }

}
