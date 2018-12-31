<?php
/**
 * Created by PhpStorm.
 * User: nayana.rane
 * Date: 2018-12-24
 * Time: 14:41
 */

namespace Drupal\alexa_custom\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Psr\Log\LoggerInterface;

/**
 * Provides a Alexa Resource
 *
 * @RestResource(
 *   id = "alexa_resource",
 *   label = @Translation("Alexa Resource"),
 *   serialization_class = "",
 *   uri_paths = {
 *     "canonical" = "/alexa_custom/alexa_resource/{site}",
 *     "https://www.drupal.org/link-relations/create" = "/alexa_custom/alexa_resource"
 *   }
 * )
 */
class AlexaResource extends ResourceBase {

  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */
  public function get() {
    $response = ['message' => 'Hello, this is a rest service.....'];
    return new ResourceResponse($response);
  }

  /**
   * Responds to POST requests.
   *
   * Returns a list of bundles for specified entity.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function post($data) {
    $response = $data;
    return  new ResourceResponse($response);
  }
}