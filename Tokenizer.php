<?php
namespace KTemplate;
class Tokenizer{
    protected $str;

    protected $node;

    protected $pos;

    protected $tokens = array();

    function __construct(Node\Node $node){
        $this->str = (string)$node;
        $this->node = $node;
        $this->pos = 0;
    }


    /**
     * Get the next token
     * @return Token
     */
    function next(){
        while($this->hasTokens()){
            $char = $this->str[$this->pos];
            if(in_array($char, array('\'', '"'))){ 
                return $this->str($char);
            }elseif(ctype_digit($char)){
                return $this->number();
            }elseif(ctype_alpha($char) || $char == '_'){
                return $this->alphanum();
            }elseif(in_array($char, array('|', '[', ']', '.', ',', ':', '='))){
                $this->pos++;
                return new Token(ord($char), null);
            }
            $this->pos++;
        }
        return NULL;
    }


    /**
     * Return True if has token
     * @return bool
     */
    function hasTokens(){
        return isset($this->str[$this->pos]);
    }

    function str($start){
        $this->pos++;
        $offset = $this->pos;
        do{
            $pos = strpos($this->str, $start, $offset);
            $offset = $pos+1;
        }while($pos && ($this->str[$pos-1] == '\\'));
        if($pos === FALSE){
            throw new ParseException($this->node, 'unclosed string');  
        }

        /*calculate lenght of str end - init*/
        $lenght = $pos - $this->pos;
        $buffer = substr($this->str, $this->pos,  $lenght);
        $this->pos = $pos  + 1;
        return new Token(Token::T_STRING, $buffer);
    }

    /**
     * Get token
     * @param string $func name of function
     * @return string
     */
    function getToken($func){
        $buffer = '';
        while($this->hasTokens()){
            $char = $this->str[$this->pos];
            if(!call_user_func($func, $char)){
                break;//salgo fin de la cadena
            }
            $buffer .= $char;
            $this->pos++;
        }
        return $buffer;
    }

    /**
     * Get a number sentence token
     * @return Token
     */
    function number(){
        $buffer = $this->getToken('ctype_digit');
        return new Token(Token::T_NUMBER, $buffer);
    }

    function alphanum(){
        $buffer =  $this->getToken('KTemplate\Tokenizer::ctype_alphadash');
        /*verifico si es una palabra reservada*/
        $word = trim($buffer);
        $key = array_search($word, Token::$ALL_TOKEN);  
        if($key !== FALSE){
            return new Token($key, $word);
        } 
        return  new Token(Token::T_IDENT, $word);
    }

    /**
     * Return all token
     * @return Array
     */
    public function getTokens(){
        while (($t = $this->next())) {
            $this->tokens[] = $t;
        }
        return $this->tokens;
    }

    static function ctype_alphadash($char){
        return ctype_alpha($char) || $char == '_';
    }
    
}