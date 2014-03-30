<?php
namespace KTemplate;
use KTemplate\Node\NodeList;
use KTemplate\Node\TextNode;
use KTemplate\Node\PrintNode;
use KTemplate\Node\ExecNode;
use KTemplate\Node\CommentNode;
use KTemplate\Generators\Generators;
use KTemplate\Generators\Output;
class Parse{

    /**
     * Nested store for blocks
     */
    protected $nested = array();

    /**
     * Output object
     */
    protected $output = null;

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
    function generate($compile, $id){
        $this->output = new Output($compile);
        $this->current = new TextNode(1);
        Generators::init($id, $this->output);
        $nLine = 0;
        $file = fopen($this->file, 'r');
        while(($this->buffer = fgets($file))) {
            $nLine++;
            $this->inLine($nLine);
        }
        fclose($file);
        $this->output->fwrite('}');
    }

    /**
     * Process a line
     * @param NodeList $pila stack of node
     * @param int $nLine 
     * @return void
     */
    protected function inLine($nLine){
        while($this->buffer){
            $pos = strpos($this->buffer, $this->getToken());
            if(($pos !== false) && $this->getCond($pos)){
                /*add to current node*/
                $this->current->addContent(substr($this->buffer, 0, $pos));
                /*other node*/
                Generators::code($this->current, $this->output, $this);
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


    function addNested($val){
        $this->nested[] = $val;
        $this->output->indUp();
    }

    function lastNested($val){
        $last = array_pop($this->nested);
        if($last != $val)
            throw $this->parseError("Bad Closed");
        $this->output->indDown();
    }

    /**
     * Thow a parse Exception
     * @param string $str message for exception
     */
    function parseError($str){
        return new ParseException($this->current, $str);
    }
}