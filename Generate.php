<?php
namespace KTemplate;

use KTemplate\Node\NodeList;
use KTemplate\Generators\Output;

class Generate{
	protected $nested = array();
	protected $nodeList = null;
	protected $node = null;

	protected $id = null;

	function __construct(NodeList $nodes, $name, $vars, Output $out){
		$this->nodeList = $nodes;
		$this->name = $name;
		$this->output = $out;
	}

	function generate(){
		$this->init();
		foreach ($this->nodeList as  $node) {
			$this->node = $node;
			$token = $node->stack();
			/*Se saca el primero para conocer el contextoo*/
			$first =  array_shift($token);
			$name = ucfirst($first->name());
			$class = "\\KTemplate\\Generators\\{$name}Generator";
			if(class_exists($class)){
				$obj = new $class($token, $this);
				echo  $obj->generate();
			}
		}
		$this->output->write('}');
	}

	function nl($str){
		$this->output->writeln($str);
	}

	function init(){
		$name = $this->name;
		$this->nl('<?php');
		$this->nl('/*generation*/');
		$this->nl("function _$name(\$vars){");
		$this->nl('extract($vars);');
	}


	function addNested($val){
		$this->nested[] = $val;
		$this->output->tabInc();
	}

	function lastNested($val){
		$last = array_pop($this->nested);
		if($last != $val)
			throw $this->parseError("Bad Closed");
		$this->output->tabDec();
	}

	function parseError($str){
		return new ParseException($this->node, $str);
	}

	


}