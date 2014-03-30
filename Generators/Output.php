<?php
namespace KTemplate\Generators;
class Output extends \SplFileObject{
    
    protected $tabnumber = 0;

    function __construct($filename){
        parent::__construct ($filename, 'w');
    }

    function writeln($str){
        $tab = str_repeat('  ', $this->tabnumber);
        $this->fwrite("{$tab}{$str}\n");
    }

}