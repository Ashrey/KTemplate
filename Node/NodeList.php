<?php
namespace KTemplate\Node;
class NodeList implements \IteratorAggregate {
	protected $list = array();

	function add(Node $node){
		$this->list[] = $node;
	}

	function getIterator() {
        return new \ArrayIterator( $this->list );
    }

}