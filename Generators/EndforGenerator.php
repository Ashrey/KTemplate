<?php
namespace KTemplate\Generators;
use KTemplate\Token;

class EndforGenerator extends Generators{

	function generate(){
		$this->output->indDown()->writeline('}');
		$this->parse->lastNested(Token::T_FOR);
        $this->end();
	}
}