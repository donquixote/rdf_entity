[![Build Status](https://travis-ci.org/ec-europa/rdf_entity.svg?branch=8.x-1.x)](https://travis-ci.org/ec-europa/rdf_entity)

Mainly, [RDF Entity](https://www.drupal.org/project/rdf_entity) provides an
entity type (`rdf_entity`) that uses the
[SPARQL](https://en.wikipedia.org/wiki/SPARQL) backend provided by [SPARQL
Entity Storage](https://www.drupal.org/project/sparql_entity_storage) module.
The entity type can be used as it is, can be extended or, simply, used as a good
use case of the [SPARQL Entity
Storage](https://www.drupal.org/project/sparql_entity_storage) module. 

### Updating from `1.0-alpha16` to `alpha17`

With `1.0-alpha17`, the SPARQL storage has been [split out, as a standalone
module](https://github.com/ec-europa/rdf_entity/issues/17). Moving services from
one module to the other is impossible with the actual Drupal core. See the
[related Drupal core issue](https://www.drupal.org/project/drupal/issues/2863986)
for details.

As this module is in alpha, we would normally not provide an upgrade path.
However, we have since become aware of a number of websites that we have an interest in supporting, that use `rdf_entity`, and that need a reliable upgrade path.

We recommend the following steps in order to update a server in production:

1. The update process is split in two consecutive deployments.
1. Install an empty version of the `sparql_entity_storage` module:
   ```
   $ composer require drupal/sparql_entity_storage:dev-empty-module
   ```
1. Enable the `sparql_entity_storage` module, export to config.
1. Deploy the changes to production.
   (A typical deployment operation will include `drush cr`, `drush updb -y` and `drush cim -y`.)
1. Require `drupal/rdf_entity:^1.0-alpha17`, and perform a second deployment.

Please open an issue if any problems occur during this update process.

If the module is / was used for [RDF SKOS](https://github.com/openeuropa/rdf_skos), please follow the more relevant instructions over there.
