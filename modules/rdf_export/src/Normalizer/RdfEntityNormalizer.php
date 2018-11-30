<?php

namespace Drupal\rdf_export\Normalizer;

use Drupal\rdf_export\RdfSerializer;
use Drupal\serialization\Normalizer\FieldableEntityNormalizerTrait;

/**
 * Converts the Drupal entity object structure to a HAL array structure.
 */
class RdfEntityNormalizer extends NormalizerBase {

  use FieldableEntityNormalizerTrait;

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var string
   */
  protected $supportedInterfaceOrClass = 'Drupal\rdf_entity\RdfInterface';

  /**
   * RdfEntityNormalizer constructor.
   *
   * @param \Drupal\rdf_export\RdfSerializer $rdfSerializer
   *   RDF Serializer service.
   */
  public function __construct(RdfSerializer $rdfSerializer) {
    $this->rdfSerializer = $rdfSerializer;
  }

  /**
   * {@inheritdoc}
   */
  public function normalize($entity, $format = NULL, array $context = []) {
    return ['_rdf_entity' => $this->rdfSerializer->serializeEntity($entity, $format)];
  }

}
