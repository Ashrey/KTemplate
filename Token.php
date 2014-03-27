<?php
namespace KTemplate;

class Token{
    const T_COMMENT    = 0;
    const T_NUMBER     = 1;
    const T_STRING     = 2;
    const T_ALPHANUM   = 3;
    const T_STREAM     = 4;
    const T_IDENT      = 5;

    const T_PRINT      = 16;
    const T_IF         = 17;
    const T_ELSE       = 18;
    const T_ELSEIF     = 19;
    const T_ENDIF      = 20;

    const T_FOR        = 21;
    const T_ENDFOR     = 22;
    const T_IN         = 23;

    const T_COMMA      = 44;
    const T_DOT        = 46;  /* . */
    const T_O_CORCH    = 91;  /* [ */
    const T_C_CORCH    = 93;  /* ] */
    const T_PIPE       = 124; /* | */


    public static $ALL_TOKEN = array(
        self::T_IDENT    => 'ident',
        self::T_PRINT    => 'print',
        self::T_COMMENT  => 'comment',
        self::T_STREAM   => 'stream',
        self::T_NUMBER   => 'number',
        self::T_STRING   => 'str',
        self::T_ALPHANUM => 'alphanum',
        self::T_FOR      => 'for',
        self::T_ENDFOR   => 'endfor',
        self::T_IN       => 'in',
        self::T_IF       => 'if',
        self::T_ELSE     => 'else',
        self::T_ELSEIF   => 'elseif',
        self::T_PIPE     => 'pipe',
    );
    
    protected $value;
    protected $type;

    /**
     * 
     */
    function __construct($type, $value){
        $this->type  = $type;
        $this->value = $value;
    }

    /**
     * Return the type of token
     * @return string 
     */
    function getType(){
        return $this->type;
    }

    /**
     * Return the value of token
     * @return string 
     */
    function getValue(){
        return $this->value;
    }

    /**
     * Return if token is the type $type
     * @param int $type 
     * @return bool
     */
    function is($type){
        return $this->type === $type;
    }

    /**
     * Return if token is the type in $args
     * @param Array $args
     * @return bool
     */
    function in(Array $args){
        return in_array($this->type, $args);
    }

     /**
     * Return the lenght of token
     * @return int
     */
    function length(){
        return strlen($this->value);
    }

     /**
     * Return the name of token
     * @return string
     */
    function name(){
        return Token::$ALL_TOKEN[$this->type];
    }
}