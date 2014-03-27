<?php
namespace KTemplate\Node;
use KTemplate\Tokenizer;

abstract class Node{
	const END_TOKEN = '';

	protected $_line = 0;

	protected $_content = ''; 

	function __construct($line){
		$this->_line = $line;
	}

	public function stack(){
		$t = new Tokenizer($this);
		return $t->getTokens();
	}

	public function getLine(){
		return $this->_line;
	}

	
	function addContent($a){
		$this->_content .= $a;
	}

	function __toString(){
		return $this->_content;
	}
}