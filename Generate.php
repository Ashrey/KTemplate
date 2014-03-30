<?php
namespace KTemplate;

use KTemplate\Node\Node;
use KTemplate\Generators\Output;

class Generate{
	
	protected $node = null;

	protected $output = null;

	function __construct(Node $node, Output $out){
		$this->node = $node;
		$this->output = $out;
	}

	function generate($parse){
		$token = $this->node->stack();
		/*Se saca el primero para conocer el contextoo*/
		$first =  array_shift($token);
		$name = ucfirst($first->name());
		$class = "\\KTemplate\\Generators\\{$name}Generator";
		if(class_exists($class)){
			$obj = new $class($token, $this, $parse);
			echo  $obj->generate();
		}
	}

	function nl($str){
		$this->output->writeln($str);
	}

	static function init($name, $output){
		$output->writeln('<?php');
		$output->writeln('/*generation*/');
		$output->writeln("function _$name(\$vars){");
		$output->writeln('extract($vars);');
	}
}