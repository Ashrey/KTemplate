<?php
namespace KTemplate\Generators;
use KTemplate\Token;

class EndifGenerator extends Generators{
    
    function generate(){
        $this->output->indDown()->writeline('}');
        $this->parse->lastNested(Token::T_IF);
        $this->end();
    }
}