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

    /**
     * Init of nested block
     * @param int $val typee of block
     */
    function nested($val){
        $this->gen->addNested($val);
    }

    /**
     * Get the next token
     * @return Token
     */
    function next(){
        $val = array_shift($this->stack);
        if(is_null($val)){
            $this->exception('No more tokens!');
        }
        return  $val;
    }

    /**
     * Get the next token and verifique the type
     * @return Token
     */
    function nextRequire($val){
        $token = $this->token();
        if(!$token->is($val)){
            $this->exception('Expenting '. $val);
        }
        return $token;
    }

    /**
     * Mark the end of sentence
     */
    function end(){
        if(!empty($this->stack)){
            $this->exception('Un xpenting mode tokens');
        }
    }

    /**
     * Thow Parse Exception with message $str
     * @param string $str
     */
    function exception($str){
        throw $this->gen->parseError($str);
    }

    /**
     * Write new line with identation
     */
    function nl($str){
        $this->gen->nl($str);
    }

    abstract function generate();
}