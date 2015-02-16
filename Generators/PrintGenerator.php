<?php
namespace KTemplate\Generators;
use KTemplate\Token;
use KTemplate\Filter\Filter;
class PrintGenerator extends Generators{

    function generate(){
        $token = $this->nextRequire(Token::T_STREAM, Token::T_IDENT, Token::T_STRING );
        return $this->applyFilter($token);
    }
}