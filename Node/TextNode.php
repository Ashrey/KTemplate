<?php
namespace KTemplate\Node;
use KTemplate\Token;

class TextNode extends Node{
	const END_TOKEN = '';

	function stack(){
		return array(
			new Token(Token::T_PRINT, 1),
			new Token(Token::T_STREAM, $this->_content)
		);
	}
}
