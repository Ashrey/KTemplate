<?php
namespace KTemplate\Generators;
use KTemplate\Token;
use KTemplate\Parse;
use KTemplate\Filter\Filter;
use KTemplate\Node\Node;
use KTemplate\Generators\Output;

class Compiler{
    /**
     * Is printer token?
     * @var boolean
     */
    protected $_echo = FALSE;

    /**
     * Output class
     * @var Output
     */
    protected $output = null;

    function __construct(Node $node, Output $output){
        var_dump($node);
        return;
        $this->output = $output;
        /*first*/
        $first =  $node->next();
        $this->do_print($first);
        $name = ucfirst($first->name());
        $class = "\\KTemplate\\Generators\\{$name}Generator";
        $value =  ucfirst($first->getValue());
        $tag   = "\\KTemplate\\Generators\\Tag\\{$value}Tag";
        if(class_exists($class)){
            $obj = new $class($node, $parse, $output);
            $obj->generate();
        }elseif($name == 'Ident' && class_exists($tag)){
            $obj = new $tag($node, $parse, $output);
            $obj->generate();
        }elseif(!in_array($name, array('Print', 'Comment'))){
            throw new \RuntimeException("Unexpected  $name - $value - $tag"); 
        }
        
    }


    function do_print($first){
        if($first->is(Token::T_PRINT)){
            if($this->_echo){
                $this->output->write(', ');
            }else{
                $this->_echo = TRUE;
                $this->output->startLine()->write('echo ');
            }
        }elseif( $this->_echo){
            $this->_echo = FALSE;
            $this->output->writeln(';');
        }
    }

    /**
     * Generate a header of file
     * @param  string $name   Hash name for function
     * @param  [type] $output [description]
     * @return [type]         [description]
     */
    static function init($name, $output){
        $output->writeline('<?php');
        $output->writeline('/*generation*/');
        $output->writeline("function _$name(\$vars){");
        $output->indUp();
        $output->writeline('extract($vars);');
    }

}