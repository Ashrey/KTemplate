<?php
namespace KTemplate\Node;
use KTemplate\Token;

class PrintNode extends Node{
	const END_TOKEN = '}}';

	function stack(){
		$stack = parent::stack();
		array_unshift($stack, new Token(Token::T_PRINT, 1));
		return $stack;
	}
}