<?php
namespace KTemplate\Generators;
class Output extends \SplFileObject{
    
    protected $tabnumber = 0;

    function __construct($filename){
        parent::__construct ($filename, 'w');
    }

    /**
     * Add more indentation
     */
    function indUp(){
        $this->tabnumber++;
        return $this;
    }

    /**
     * Reduce indentation
     */
    function indDown(){
        $this->tabnumber--;
        return $this;
    }

    /**
     * start a line
     */
    function startLine(){
        $this->fwrite(str_repeat('    ', $this->tabnumber));
        return $this;
    }

    /**
     * Write string and add newline
     */
    function writeln($str){
        $this->fwrite("$str\n");
        return $this;
    }
    
    /**
     * Write a line
     */
    function writeline($str){
        $this->startLine();
        $this->fwrite("{$str}\n");
        return $this;
    }

    function write($str){
        $this->fwrite($str);
        return $this;
    }
}