<?php
namespace KTemplate\Generators;
use KTemplate\Token;
use KTemplate\Parse;
use KTemplate\Filter\Filter;
use KTemplate\Node\Node;
use KTemplate\Generators\Output;

abstract class Generators{
    /**
     * Stack of tokens
     * @var Array
     */
    protected $stack = array();
   
    /**
     * Parse Object
     * @var Parse
     */
    protected $parse   = null; 

    /**
     * Ouput object
     * @var Output
     */
    protected $output = null;

    function __construct(Array $stack, Parse $parse, Output $o){
        $this->stack  = $stack;
        $this->parse  = $parse;
        $this->output = $o;
    }

    abstract function generate();

    /**
     * Init of nested block
     * @param int $val typee of block
     */
    function nested($val){
        $this->parse->addNested($val);
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
    function nextRequire(){
        $token = $this->next();
        $args = func_get_args();
        if(!$token->in($args)){

            $this->exception('Expenting '. implode(', ', $args));
        }
        return $token;
    }

    /**
     * Return if has more token
     * @return bool
     */
    function hasToken(){
        return !empty($this->stack);
    }

    /**
     * Return if next token is type
     * @return bool
     */
    function nextIs(){
        $token  =  $this->stack[0];
        return $token->in(func_get_args());
    }

    /**
     * Return the next token if is 
     * @return Token 
     */
    function nextIfIs(){
        if(!$this->hasToken()) return null;
        $token  =  $this->stack[0];
        return $token->in(func_get_args()) ? $this->next() : null;
    }

    /**
     * Mark the end of sentence
     */
    function end(){
        if($this->hasToken()){
            $this->exception('Un xpenting mode tokens');
        }
    }

    /**
     * Thow Parse Exception with message $str
     * @param string $str
     */
    function exception($str){
        throw $this->parse->parseError($str);
    }

    /**
     * Write new line with identation
     */
    function nl($str){
        $this->output->writeln($str);
    }

    function getFilter($filter){
        $name =  'KTemplate\\Filter\\'.ucfirst($filter).'Filter';
        if(!class_exists("$name")){
            throw new \Exception("Filter $filter do not exits"); 
        }
        $f = new $name();
        if(!$f instanceof Filter){
            throw new \Exception("$filter is  not a Filter"); 
        }
        return $f;
    }

    /**
     * Apply filter on $idem
     * @param string $str identifiquer
     * @return string
     */
    function applyFilter($str){
        while($this->nextIfIs(Token::T_PIPE)){
            $token = $this->nextRequire(Token::T_IDENT);
            /*Tiene argumentos*/
            $arg = array();
            if($this->nextIfIs(Token::T_DDOT)){
                $arg[] = $this->nextRequire(Token::T_IDENT, Token::T_NUMBER, Token::T_STRING)->getValue();
            }
            $filter = $this->getFilter($token->getValue());
            $str = $filter->generate($str, $arg);
        }
        return $str;
    }

    static function code($node, $output, $parse){
        $token = $node->stack();
        /*Se saca el primero para conocer el contexto*/
        $first =  array_shift($token);
        $name = ucfirst($first->name());
        $class = "\\KTemplate\\Generators\\{$name}Generator";
        if(class_exists($class)){
            $obj = new $class($token, $parse, $output);
            $obj->generate();
        }
    }


    static function init($name, $output){
        $output->writeln('<?php');
        $output->writeln('/*generation*/');
        $output->writeln("function _$name(\$vars){");
        $output->writeln('extract($vars);');
    }
}