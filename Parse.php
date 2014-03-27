<?php
namespace KTemplate;
use KTemplate\Node\NodeList;
use KTemplate\Node\TextNode;
use KTemplate\Node\PrintNode;
use KTemplate\Node\ExecNode;
use KTemplate\Node\CommentNode;
class Parse{

	protected $file;

	protected $vars;

	protected $buffer = '';

	protected $current = null;


	function __construct($file, $vars){
		$this->file = $file;
		$this->vars = $vars;
	}

	function getNodes(){
		$pila = new NodeList();
		$this->current = new TextNode(1);
		$lines = file($this->file);
		$nLine = 0; /*Number of line*/
		foreach ($lines as $l) {
			$nLine++;
			$this->buffer = $l;
			while($this->buffer){
				$pos = strpos($this->buffer, $this->getToken());
				if(($pos !== false) && $this->getCond($pos)){
					$this->current->addContent(substr($this->buffer, 0, $pos));
					$pila->add($this->current);
					$this->current = $this->getNode($this->buffer[$pos + 1], $nLine);
					$this->buffer = substr($this->buffer, $pos+2);
				}else{
					$this->current->addContent($this->buffer);
					$this->buffer = false;
				}
			}
		}
		return $pila;
	}

	protected function getToken(){
		$c = $this->current;
		return $this->current instanceof TextNode ?  '{'  :  $c::END_TOKEN;
	}

	protected function getCond($pos){
		$str = $this->buffer;
		return $this->current instanceof TextNode ? 
			isset($str[$pos + 1]) && in_array($str[$pos + 1], array('#', '{', '%')):
			TRUE;
	}


	protected function getNode($token, $line){
		if(!$this->current instanceof TextNode){
			return new TextNode($line);
		}

		if($token == '#'){
			return new CommentNode($line);
		}

		if($token == '%'){
			return new ExecNode($line);
		}

		if($token == '{'){
			return new PrintNode($line);
		}


	}
}