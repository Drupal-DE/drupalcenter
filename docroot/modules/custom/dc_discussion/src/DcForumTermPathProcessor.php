<?php

namespace Drupal\dc_discussion;

use Drupal\Core\PathProcessor\InboundPathProcessorInterface;
use Drupal\Core\PathProcessor\OutboundPathProcessorInterface;
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\taxonomy\Entity\Term;
use Symfony\Component\HttpFoundation\Request;

/**
 * PathProcessor for forum terms.
 *
 * @package Drupal\dc_discussion
 */
class DcForumTermPathProcessor implements InboundPathProcessorInterface, OutboundPathProcessorInterface {

  /**
   * {@inheritdoc}
   */
  public function processInbound($path, Request $request) {
    // Process path "forum/c/[term]".
    if (preg_match('|^/forum/c/(?P<tid>[0-9]+)|', $path, $matches)) {
      $path = '/taxonomy/term/' . $matches['tid'];
    }

    return $path;
  }

  /**
   * {@inheritdoc}
   */
  public function processOutbound($path, &$options = array(), Request $request = NULL, BubbleableMetadata $bubbleable_metadata = NULL) {
    // Rewrite path "taxonomy/term/[term]".
    if (preg_match('|^/taxonomy/term/(?P<tid>[0-9]+)|', $path, $matches)) {
      // We have to load node object to retrieve actual type.
      $term = Term::load($matches['tid']);
      if ($term->bundle() == 'forums') {
        $path = '/forum/c/' . $matches['tid'];
      }
    }

    return $path;
  }

}
