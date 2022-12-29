<?php

namespace Drupal\adsense\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Controller for the Custom Search Engine results page.
 */
class CseResultsController extends ControllerBase {

  /**
   * The request stack used to access request globals.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Constructs a new CseV2ResultsController controller.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   */
  public function __construct(RequestStack $request_stack) {
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('request_stack')
    );
  }

  /**
   * Display the search results page.
   *
   * @return array
   *   Markup for the page with the search results.
   */
  public function display() {
    $config = $this->config('adsense.settings');
    $width = $config->get('adsense_cse_frame_width');
    $country = $config->get('adsense_cse_country');

    if ($config->get('adsense_test_mode')) {
      $content = [
        '#theme' => 'adsense_ad',
        '#content' => ['#markup' => nl2br("Results\nwidth = $width\ncountry = $country")],
        '#classes' => ['adsense-placeholder'],
        '#width' => $width,
        '#height' => 100,
      ];
    }
    else {
      global $base_url;

      // Log the search keys.
      $this->getLogger('AdSense CSE v1')->notice('Search keywords: %keyword', [
        '%keyword' => urldecode($this->requestStack->getCurrentRequest()->query->get('q')),
      ]);

      $content = [
        '#theme' => 'adsense_cse_results',
        '#width' => $width,
        '#country' => $country,
        // http://www.google.com/afsonline/show_afs_search.js
        '#script' => $base_url . '/' . drupal_get_path('module', 'adsense') . '/js/adsense_cse-v1.results.js',
      ];
    }
    return $content;
  }

}
