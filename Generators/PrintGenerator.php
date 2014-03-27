<?php
namespace KTemplate\Generators;
use KTemplate\Token;

class PrintGenerator extends Generators{

    function generate(){
        $token = $this->next();
        $str = $this->getPrint($token);
        $this->nl("echo $str ;\n");
    }

    function getPrint($token){
        switch ($token->getType()) {
            case Token::T_STREAM:
                return "'". addslashes($token->getValue()) . "'";
                break;
            case Token::T_IDENT :
                return '$'.$token->getValue();
            case Token::T_ALPHANUM:
                return self::printAlphanum($val, $tokens);
            default:
                return "''";
                break;
        }
    }
}