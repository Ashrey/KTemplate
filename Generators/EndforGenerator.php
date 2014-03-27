<?php
namespace KTemplate\Generators;
use KTemplate\Token;

class EndforGenerator extends Generators{

	function generate(){
		$this->nl("}\n");
		$this->gen->lastNested(Token::T_FOR);
	}
}