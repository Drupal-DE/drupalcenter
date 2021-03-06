<?php

namespace Drupal\dc_discussion;

use Drupal\Core\PathProcessor\InboundPathProcessorInterface;
use Drupal\Core\PathProcessor\OutboundPathProcessorInterface;
use Drupal\Core\Render\BubbleableMetadata;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\Request;

/**
 * PathProcessor for forum nodes.
 *
 * @package Drupal\dc_discussion
 */
class DcForumPathProcessor implements InboundPathProcessorInterface, OutboundPathProcessorInterface {

  /**
   * {@inheritdoc}
   */
  public function processInbound($path, Request $request) {
    // Process path "diskussion/starten".
    if ($path == "/diskussion/starten") {
      $path = "/node/add/forum";
    }

    // Process path "diskussion/[nid]/bearbeiten".
    if (preg_match('|^/diskussion/([0-9]*)/bearbeiten(/.*)?|', $path, $matches)) {
      $path = '/node/' . $matches[1] . '/edit';
    }

    // Process path "diskussion/[nid]/beantworten".
    if (preg_match('|^/diskussion/([0-9]*)/beantworten(/.*)?|', $path, $matches)) {
      // @todo use comment.
//      $path = '/node/' . $matches[1] . '/discussion_answer';
    }

    return $path;
  }

  /**
   * {@inheritdoc}
   */
  public function processOutbound($path, &$options = array(), Request $request = NULL, BubbleableMetadata $bubbleable_metadata = NULL) {
    // Rewrite path "node/add/forum".
    if ($path == "/node/add/forum") {
      $path = "/diskussion/starten";
    }

    // Rewrite path "node/[node]/edit".
    if (preg_match('|^/node/([0-9]*)/edit(/.*)?|', $path, $matches)) {
      // We have to load node object to retrieve actual type.
      $node = Node::load($matches[1]);
      if ($node->bundle() == 'forum') {
        $path = '/diskussion/' . $matches[1] . '/bearbeiten';
      }
    }

    // Rewrite path "node/[node]/discussion_answer".
    if (preg_match('|^/node/([0-9]*)/discussion_answer(/.*)?|', $path, $matches)) {
      // We have to load node object to retrieve actual type.
      $node = Node::load($matches[1]);
      if ($node->bundle() == 'forum') {
        $path = '/diskussion/' . $matches[1] . '/beantworten';
      }
    }

    return $path;
  }

}
