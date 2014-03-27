<?php
namespace KTemplate\Generators;
use KTemplate\Token;

class ForGenerator extends Generators{

	function generate(){
		$this->nested(Token::T_FOR);
		$this->nl("foreach(");
		$t1  =  $this->nextAndRequire(Token::T_IDENT);
		$t2  =  $this->token();
		if($t2->is(Token::T_COMMA)){
			$t3 = $this->nextAndRequire(Token::T_IDENT);
			$t4 = $this->nextAndRequire(Token::T_IN);
			$t5 = $this->nextAndRequire(Token::T_IDENT);
			echo  '$', $t5->getValue();
			echo ' as ';
			echo '$', $t1->getValue();
			echo ' => ';
			echo '$', $t3->getValue();

		}elseif($t2->is(Token::T_IN)){
			$t3 = $this->nextAndRequire(Token::T_IDENT);
			echo  '$', $t3->getValue();
			echo ' as ';
			echo '$', $t1->getValue();
		}else{
			$this->exception('Unespexting');
		}
		echo "){\n";
	}
}