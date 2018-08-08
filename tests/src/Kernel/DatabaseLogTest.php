<?php

declare(strict_types = 1);

namespace Drupal\Tests\rdf_entity\Kernel;

use Drupal\Core\Database\Database;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\rdf_entity\Traits\RdfDatabaseConnectionTrait;

/**
 * Tests the query logging.
 *
 * @group rdf_entity
 *
 * @coversDefaultClass \Drupal\rdf_entity\Database\Driver\sparql\Connection
 */
class DatabaseLogTest extends KernelTestBase {

  use RdfDatabaseConnectionTrait;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->setUpSparql();
  }

  /**
   * Tests the log.
   *
   * @dataProvider provider
   */
  public function testLog(string $method, string $query, array $args): void {
    Database::startLog('log_test', 'sparql_default');
    $this->sparql->{$method}($query);
    $log = $this->sparql->getLogger()->get('log_test');

    $this->assertCount(1, $log);

    $log_entry = reset($log);
    $this->assertEquals($query, $log_entry['query']);
    $this->assertSame($args, $log_entry['args']);
    $this->assertEquals('default', $log_entry['target']);
    $this->assertEquals('double', gettype($log_entry['time']));
    $this->assertGreaterThan(0, $log_entry['time']);
    // @todo Inspect also $log_entry['caller'] when
    // https://www.drupal.org/project/drupal/issues/2867788 lands.
    // @see https://www.drupal.org/project/drupal/issues/2867788
  }

  /**
   * Data provider for ::testLog().
   *
   * @return array
   *   Test cases.
   *
   * @see DatabaseLogTest::testLog()
   */
  public function provider(): array {
    return [
      'query' => ['query', 'SELECT DISTINCT ?s ?p ?o WHERE { ?s ?p ?o } LIMIT 100', []],
      'update' => ['update', 'CLEAR GRAPH <http://example.com>;', []],
    ];
  }

}
