<?php
namespace KTemplate\Generators;
use KTemplate\Generate;

abstract class Generators{
	protected $stack = array();
	protected $gen   = null; 

	function __construct(Array $stack, Generate $generator){
		$this->stack = $stack;
		$this->gen   = $generator;
	}

	function nested($val){
		$this->gen->addNested($val);
	}

	function token(){
		$val = array_shift($this->stack);
		if(is_null($val)){
			$this->exception('No more tokens!');
		}
		return  $val;
	}

	function nextAndRequire($val){
		$token = $this->token();
		if(!$token->is($val)){
			$this->exception('Expenting '. $val);
		}
		return $token;
	}

	function exception($str){
		throw $this->gen->parseError($str);
	}

	function nl($str){
		$this->gen->nl($str);
	}

	abstract function generate();
}