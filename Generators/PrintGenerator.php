<?php
namespace KTemplate\Generators;
use KTemplate\Token;
use KTemplate\Filter\Filter;
class PrintGenerator extends Generators{

    function generate(){
        $token = $this->nextRequire(Token::T_STREAM, Token::T_IDENT, Token::T_STRING );
        $str = $this->getPrint($token);
        $this->nl("echo $str ;\n");
        $this->end();
    }

    function getPrint($token){
        switch ($token->getType()) {
            case Token::T_STREAM:
                  return $this->getStream($token);
                break;
            case Token::T_IDENT :
                return $this->getIdem($token);
            case Token::T_STRING :
                return $this->getStr($token);
        }
    }

    /**
     * Get a Stream
     * @param Token $token 
     * @return string
     */
    function getStream($token){
        return "'".  addcslashes($token->getValue(), "'\\") . "'";
    }

    /**
     * Get a identifiquer
     * @param Token $token 
     * @return string
     */
    function getIdem($token){
        $str = '$'.$token->getValue();
        return $this->applyFilter($str);
    }

    /**
     * Get a string
     * @param Token $token 
     * @return string
     */
    function getStr($token){
        $str = $token->getValue();
        return $this->applyFilter($str);
    }
}