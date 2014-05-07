<?php
namespace KTemplate\Generators;
use KTemplate\Token;

class ElseGenerator extends Generators{

    function generate(){
        $this->output->indDown()->startLine()->writeln("}else{")->indUp();
        $this->end();
    }
}