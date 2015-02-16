<?php
namespace KTemplate\Generators;
use KTemplate\Filter\Filter;
use KTemplate\Generators\Output;
use KTemplate\Node\Node;
use KTemplate\Parse;
use KTemplate\Token;

/**
 * @method \Exception exception
 * @method \Token nextIfIs
 * @method \Token nextRequire
 * 
 */

abstract class Generators {
	/**
	 * Parse Object
	 * @var Parse
	 */
	protected $parse = null;

	/**
	 * Ouput object
	 * @var Output
	 */
	protected $output = null;

	protected $node = null;

	function __construct(Node $node) {
		//$this->parse = $parse;
		//$this->output = $o;
		$this->node = $node;
	}

	abstract function generate();

	/**
	 * Init of nested block
	 * @param int $val typee of block
	 */
	function nested($val) {
		$this->parse->addNested($val);
	}

	/**
	 * Write new line with identation
	 */
	function nl($str) {
		$this->output->writeln($str);
	}

	function getFilter($filter) {
		$name = 'KTemplate\\Filter\\' . ucfirst($filter) . 'Filter';
		if (!class_exists("$name")) {
			throw $this->exception("Filter $filter do not exits");
		}
		$f = new $name();
		if (!$f instanceof Filter) {
			throw $this->exception("$filter is  not a Filter");
		}
		return $f;
	}

	/**
	 * Apply filter on $idem
	 * @param string $str identifiquer
	 * @return string
	 */
	function applyFilter($str) {
		while ($this->nextIfIs(Token::T_PIPE)) {
			$token = $this->nextRequire(Token::T_IDENT);
			/*Tiene argumentos*/
			$arg = array();
			if ($this->nextIfIs(Token::T_DDOT)) {
				$arg[] = $this->nextRequire(Token::T_IDENT, Token::T_NUMBER, Token::T_STRING);
			}
			$filter = $this->getFilter($token->getValue());
			$str = $filter->generate($str, $arg);
		}
		return $str;
	}

	public function __call($name, $arguments) {
		return call_user_func_array(array($this->node, $name), $arguments);
	}
}