<?php

declare(strict_types = 1);

namespace Drupal\rdf_entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\sparql_entity_storage\Database\Driver\sparql\ConnectionInterface;
use Drupal\sparql_entity_storage\SparqlEntityStorageGraphHandlerInterface;
use EasyRdf\Graph;

/**
 * Service to serialise RDF entities into various formats.
 */
class SparqlSerializer implements SparqlSerializerInterface {

  /**
   * The Sparql connection object.
   *
   * @var \Drupal\sparql_entity_storage\Database\Driver\sparql\ConnectionInterface
   */
  protected $sparqlEndpoint;

  /**
   * The SPARQL graph handler service.
   *
   * @var \Drupal\sparql_entity_storage\SparqlEntityStorageGraphHandlerInterface
   */
  protected $graphHandler;

  /**
   * Instantiates a new RdfSerializer object.
   *
   * @param \Drupal\sparql_entity_storage\Database\Driver\sparql\ConnectionInterface $sparqlEndpoint
   *   The SPARQL connection object.
   * @param \Drupal\sparql_entity_storage\SparqlEntityStorageGraphHandlerInterface
   *   The SPARQL graph handler service.
   */
  public function __construct(ConnectionInterface $sparqlEndpoint, SparqlEntityStorageGraphHandlerInterface $graph_handler) {
    $this->sparqlEndpoint = $sparqlEndpoint;
    $this->graphHandler = $graph_handler;
  }

  /**
   * {@inheritdoc}
   */
  public function serializeEntity(ContentEntityInterface $entity, string $format = 'turtle'): string {
    $graph_uri = $this->graphHandler->getBundleGraphUri($entity->getEntityTypeId(), $entity->bundle(), $entity->graph->target_id);
    $entity_id = $entity->id();

    $query = <<<Query
SELECT ?s ?p ?o 
WHERE {
  {
    GRAPH <{$graph_uri}> {
      ?s ?p ?o .
      VALUES ?s { <{$entity_id}> } .
    }
  }
  UNION {
    GRAPH <{$graph_uri}> {
      ?s ?p ?o .
      VALUES ?o { <{$entity_id}> } .
    }
  }
}
ORDER BY ?s, ?p, ?o
Query;

    $graph = new Graph($entity->id());
    $results = $this->sparqlEndpoint->query($query);
    foreach ($results as $result) {
      $graph->add($result->s, $result->p, $result->o);
    }
    return $graph->serialise($format);
  }

}
