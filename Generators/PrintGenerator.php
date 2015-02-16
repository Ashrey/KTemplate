<?php
namespace KTemplate\Generators;
use KTemplate\Token;
use KTemplate\Filter\Filter;
class PrintGenerator extends Generators{

    function generate(){
        $token = $this->nextRequire(Token::T_STREAM, Token::T_IDENT, Token::T_STRING );
        if($this->nextIfIs(Token::T_O_CORCH)){
            $next = $this->nextRequire(Token::T_IDENT, Token::T_STRING, Token::T_NUMBER);
            $this->nextRequire(Token::T_C_CORCH);
            $token = "{$token}[$next]";
        }
        return $this->applyFilter($token);
    }
}