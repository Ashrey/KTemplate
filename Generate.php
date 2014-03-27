<?php
namespace KTemplate;

use KTemplate\Node\NodeList;

class Generate{

	protected $nested = array();
	protected $tabnumber = 1;
	
	protected $nodeList = null;
	protected $node = null;

	protected $id = null;

	function __construct(NodeList $nodes, $name, $vars){
		$this->nodeList = $nodes;
		$this->name = $name;
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
		echo '}';
		return ob_get_clean();
	}


	function init(){
		$name = $this->name;
		echo '<?php', "\n";
		echo '/*generation*/', "\n";
		echo "function _$name(\$vars){", "\n";
		echo $this->nl("extract(\$vars);\n");
	}


	function addNested($val){
		$this->nested[] = $val;
		$this->tabnumber++;
	}

	function lastNested($val){
		$last = array_pop($this->nested);
		if($last != $val)
			throw $this->parseError("Bad Closed");
		$this->tabnumber--;
	}

	function parseError($str){
		return new ParseException($this->node, $str);
	}

	function nl($str){
		echo str_repeat('  ', $this->tabnumber), $str;
	}


}