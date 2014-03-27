<?php
namespace KTemplate\Generators;
use KTemplate\Token;
use KTemplate\Filter\Filter;
class PrintGenerator extends Generators{

    function generate(){
        $token = $this->nextRequire(Token::T_STREAM, Token::T_IDENT);

        $str = $this->getPrint($token);
        $this->nl("echo $str ;\n");
    }

    function getStream($token){
        return "'".  addcslashes($token->getValue(), "'\\") . "'";
    }

    function getIdem($token){
        $str = '$'.$token->getValue();
        return $this->applyFilter($str);
    }

    function getPrint($token){
        switch ($token->getType()) {
            case Token::T_STREAM:
                  return $this->getStream($token);
                break;
            case Token::T_IDENT :
                return $this->getIdem($token);
        }
    }
}