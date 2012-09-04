<?php
// $Id$
/**
 * @file
 * Title        : NodequeuePager Helper Class
 * Created      : 2010.02.15
 * Author       : Jerod Fritz (jerod@centogram.com) - Centogram Development
 * Adaptation   : litwol (http://drupalbin.com/9359).  Rewritten to use core nodequeue functions and wrapped in module for block placement
 * Description  : Helper class to retrieve prev and next node ids from nodequeue
 */

class NodequeuePager {
  public  $sqid;
  public $wrap;
  public $nodes_in_queue = array();
  public $positions = array();

  public function __construct($sqid, $wrap = TRUE) {
    $this->sqid = $sqid;
    $this->wrap = $wrap;
    foreach ($this->nodequeue_load_nodes($sqid) as $position => $node) {
      $this->nodes_in_queue[$node->nid] = $node;
      $this->nodes_in_queue[$node->nid]->position = $position;
      $this->positions[$position] = $node;
    }
  }

  public function getNext($nid) {
    $next = $this->nodes_in_queue[$nid]->position + 1;
    if (count($this->nodes_in_queue)-1 == $this->nodes_in_queue[$nid]->position) { // last item on the list
      if ($this->wrap) {
        $next = 0;
      } else {
        return NULL;
      }
    }
    return $this->positions[$next];
  }

  public function getPrevious($nid) {
    $previous = $this->nodes_in_queue[$nid]->position - 1;
    if ( $previous == -1 ) {  // first item on the list
      if ($this->wrap) {
        $previous = count($this->nodes_in_queue)-1;
      } else {
        $previous = NULL;
      }
    }
    return $this->positions[$previous];
  }

  private function nodequeue_load_nodes($sqid, $backward = FALSE, $from = 0, $count = 0, $published_only = TRUE) {
    global $language;
    $orderby = ($backward ? "DESC" : "ASC");
    $query = db_select('node', 'n')
      ->fields('tn', array('nid'))
      ->condition('nn.sqid', $sqid)
      ->condition('tn.language', $language->language)
      ->orderBy('nn.position', $orderby)
      ->addTag('node_access');
    $query->join('nodequeue_nodes', 'nn', 'n.nid = nn.nid');
    $query->join('node', 'tn', 'tn.tnid = n.nid');

    if ($published_only) {
      $query->condition('n.status', 1);
    }

    if ($count) {
      $result = $query->range($from, $count)->execute();
    }
    else {
      $result = $query->execute();
    }

    $nodes = array();
    foreach ($result as $nid) {
      $nodes[] = node_load($nid->nid);
    }

    return $nodes;
  }
}

function theme_nodequeue_pager($sqid = 1, $nid = NULL, $wrap = TRUE) {
  $pager = new NodequeuePager($sqid, $wrap);

  $previousNode = $pager->getPrevious($nid);
  $nextNode = $pager->getNext($nid);

  if ($previousNode) {
    $previous = l($previousNode->title, 'node/'. $previousNode->nid, array('attributes'=>array('class' => 'prev-ico')));
  }
  if ($nextNode) {
    $next = l($nextNode->title, 'node/'. $nextNode->nid, array('attributes'=>array('class' => 'next-ico')));
  }

  return $previous."<i>|</i>".$next;
}