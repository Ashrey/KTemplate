<?php
namespace KTemplate\Generators;
use KTemplate\Token;

class IfGenerator extends Generators{

    function generate(){
        $this->nested(Token::T_IF);
        $this->output->startLine()->write("if(");
        $this->applyExpression();
        $this->output->writeln("){")->indUp();
        $this->end();
    }

    function applyExpression(){
        do{
            $token = $this->nextRequire(Token::T_IDENT);
            $this->output->write($token);
            if($token = $this->nextIfIs(Token::T_EQUAL)){
                $this->output->write($token);
                $token = $this->nextRequire(Token::T_IDENT, Token::T_NUMBER, Token::T_STRING);
                $this->output->write($this->applyFilter($token));
            }
        }while($this->nextIfIs(Token::T_AND, Token::T_OR));
    }
}