<?php
namespace KTemplate\Node;
use KTemplate\Token;

class CommentNode extends Node{
	const END_TOKEN = '#}';
	function stack(){
		return array(
			new Token(Token::T_COMMENT, $this->_content, $this)
		);
	}
}
