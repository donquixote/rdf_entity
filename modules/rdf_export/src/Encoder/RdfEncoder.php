<?php

namespace Drupal\rdf_export\Encoder;

use Drupal\rdf_export\RdfEncoderInterface;
use EasyRdf\Format;

/**
 * Adds RDF encoder support for the Serialization API.
 */
class RdfEncoder implements RdfEncoderInterface {

  /**
   * Static cache for supported formats.
   *
   * @var \EasyRdf\Serialiser[]
   */
  protected static $supportedFormats;

  /**
   * {@inheritdoc}
   */
  public function supportsEncoding($format) {
    return !empty(static::getSupportedFormats()[$format]);
  }

  /**
   * {@inheritdoc}
   */
  public function encode($data, $format, array $context = []) {
    if (isset($data['_rdf_entity'])) {
      return $data['_rdf_entity'];
    }
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSupportedFormats(): array {
    if (!isset(static::$supportedFormats)) {
      $container_registered_formats = \Drupal::getContainer()->getParameter('rdf_export.encoders');
      $rdf_serializers = Format::getFormats();
      static::$supportedFormats = array_intersect_key($rdf_serializers, $container_registered_formats);
    }
    return static::$supportedFormats;
  }

}
