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
		$nLine = 0;
		$file = fopen($this->file, 'r');
		while(($this->buffer = fgets($file))) {
			$nLine++;
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
		fclose($file);
		return $pila;
	}

	/**
	 * Get the token to search
	 * @return string
	 */
	protected function getToken(){
		$c = $this->current;
		return $c instanceof TextNode ?  '{'  :  $c::END_TOKEN;
	}

	/**
	 * 	Return condition for node
	 * @return bool
	 */
	protected function getCond($pos){
		$str = $this->buffer;
		return $this->current instanceof TextNode ? 
			isset($str[$pos + 1]) && in_array($str[$pos + 1], array('#', '{', '%')):
			TRUE;
	}

	/**
	 * Return node
	 */
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