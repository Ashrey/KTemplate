<?php
namespace KTemplate\Generators;
use KTemplate\Token;

class ForGenerator extends Generators{

    function generate(){
        $this->nested(Token::T_FOR);
        $this->nl("foreach(");
        $t1  =  $this->nextRequire(Token::T_IDENT);
        $t2  =  $this->nextRequire(Token::T_COMMA, Token::T_IN);
        if($t2->is(Token::T_COMMA)){
          $this->withValue($t1, $t2);
        }else{
            $this->withoutValue($t1, $t2); 
        }
        echo "){\n";
        $this->end();
    }

    /**
     * Generate foreach with values
     * @param Token $t1
     * @param Token $t2
     */
    function withValue($t1, $t2){
        $t3 = $this->nextRequire(Token::T_IDENT);
        $t4 = $this->nextRequire(Token::T_IN);
        $t5 = $this->nextRequire(Token::T_IDENT);
        echo sprintf ('$%s as $%s  => $%s ',
            $t5->getValue(), $t1->getValue(), $t3->getValue()
        );
    }

    /**
     * Generate foreach without values
     * @param Token $t1
     * @param Token $t2
     */
    function withoutValue($t1, $t2){
        $t3 = $this->nextRequire(Token::T_IDENT);
        echo  sprintf ('$%s as $%s',
            $t3->getValue(), $t1->getValue()
        );
    }
}