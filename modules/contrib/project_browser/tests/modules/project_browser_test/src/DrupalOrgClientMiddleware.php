<?php

namespace Drupal\project_browser_test;

use Drupal\Component\Serialization\Json;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

/**
 * Middleware to intercept Drupal.org API requests during tests.
 */
class DrupalOrgClientMiddleware {

  /**
   * Invoked method that returns a promise.
   */
  public function __invoke() {
    return function ($handler) {
      return function (RequestInterface $request, array $options) use ($handler) {
        $json_response = '';
        if ($request->getUri()->getPath() === '/api-d7/node.json') {
          $uri_query = $request->getUri()->getQuery();
          $covered = [
            'field_project_machine_name=awesome_module',
            'field_project_machine_name=core',
            'field_project_machine_name=metatag',
            'field_project_machine_name=cream_cheese',

          ];
          if (in_array($uri_query, $covered)) {
            $json_response = new Response(200, [], Json::encode([
              'list' => [
                ['field_security_advisory_coverage' => 'covered'],
              ],
            ]));
          }
          if ($uri_query === 'field_project_machine_name=security_revoked_module') {
            $json_response = new Response(200, [], Json::encode([
              'list' => [
                ['field_security_advisory_coverage' => 'revoked'],
              ],
            ]));
          }
          if (!empty($json_response)) {
            return new FulfilledPromise($json_response);
          }
        }

        return $handler($request, $options);
      };
    };
  }

}
