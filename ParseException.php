<?php
namespace KTemplate;
class ParseException extends \Exception{
	
	function __construct(Node\Node $node, $desc){
		$str = $desc . '. In line: ' .$node->getLine();
		parent::__construct($str);
	}
}
