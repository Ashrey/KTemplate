<?php
namespace KTemplate;
class ParseException extends \Exception {

	function __construct($line, $desc) {
		$str = $desc . '. In line: ' . $line;
		parent::__construct($str);
	}
}
