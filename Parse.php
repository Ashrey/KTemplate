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

    /**
     * Get nodes
     * @return NodeList
     */
    function getNodes(){
        $pila = new NodeList();
        $this->current = new TextNode(1);
        $nLine = 0;
        $file = fopen($this->file, 'r');
        while(($this->buffer = fgets($file))) {
            $nLine++;
            $this->inLine($pila, $nLine);
        }
        fclose($file);
        return $pila;
    }

    /**
     * Process a line
     * @param NodeList $pila stack of node
     * @param int $nLine 
     * @return void
     */
    protected function inLine(NodeList $pila, $nLine){
        while($this->buffer){
            $pos = strpos($this->buffer, $this->getToken());
            if(($pos !== false) && $this->getCond($pos)){
                /*add to current node*/
                $this->current->addContent(substr($this->buffer, 0, $pos));
                $pila->add($this->current);
                /*create new node*/
                $this->current = $this->getNode($this->buffer[$pos + 1], $nLine);
                $this->buffer = substr($this->buffer, $pos+2);
            }else{
                $this->current->addContent($this->buffer);
                $this->buffer = false;
            }
        }
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
     *  Return condition for node
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
     * @param string $token
     * @param int $line
     * @return Node
     */
    protected function getNode($token, $line){
        $arr = array(
            '#' => new CommentNode($line),
            '%' => new ExecNode($line),
            '{' => new PrintNode($line),
        );
        return ($this->current instanceof TextNode) ?
            $arr[$token] : new TextNode($line);
    }
}