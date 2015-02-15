<?php
namespace KTemplate\Node;
use KTemplate\Parse;
use KTemplate\Tokenizer;

abstract class Node {
	const END_TOKEN = '';

	protected $_content = '';

	protected $stack = null;

	/**
	 * Create a new node
	 * @param String $text number of line
	 */
	function __construct($text) {
		$this->_content = $text;
	}

	public function stack() {
		$t = new Tokenizer($this);
		$this->stack = $t->getTokens();
		return $this->stack;
	}

	public function getLine() {
		return $this->_line;
	}

	function addContent($a) {
		$this->_content .= $a;
	}

	function __toString() {
		return $this->_content;
	}

	/**
	 * Get the next token
	 * @return Token
	 */
	function next() {
		$val = array_shift($this->stack);
		if (is_null($val)) {
			$this->exception('No more tokens!');
		}
		return $val;
	}

	/**
	 * Get the next token and verifique the type
	 * @return Token
	 */
	function nextRequire() {
		$token = $this->next();
		$args = func_get_args();
		if (!$token->in($args)) {
			$this->exception('Expenting ' . implode(', ', $args));
		}
		return $token;
	}

	/**
	 * Return if has more token
	 * @return bool
	 */
	function hasToken() {
		return !empty($this->stack);
	}

	/**
	 * Return if next token is type
	 * @return bool
	 */
	function nextIs() {
		if (isset($this->stack[0])) {
			$token = $this->stack[0];
			return $token->in(func_get_args());
		} else {
			return false;
		}
	}

	/**
	 * Return the next token if is
	 * @return Token
	 */
	function nextIfIs() {
		if (!$this->hasToken()) {
			return null;
		}

		$token = $this->stack[0];
		return $token->in(func_get_args()) ? $this->next() : null;
	}

	/**
	 * Mark the end of sentence
	 */
	function end() {
		if ($this->hasToken()) {
			var_dump($this->stack);
			$this->exception('Unexpected token');
		}
	}

	/**
	 * Thow Parse Exception with message $str
	 * @param string $str
	 */
	function exception($str) {
		throw $this->parse->parseError($str);
	}
}