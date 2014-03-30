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
	function nextToken(){
		while($this->hasTokens()){
			$char = $this->str[$this->pos];
			if(in_array($char, array('\'', '"'))){ 
				return $this->str($char);
			}elseif(ctype_digit($char)){
				return $this->number();
			}elseif(ctype_alpha($char) || $char == '_'){
				return $this->alphanum();
			}else{
				switch ($char) {
					case '|':
					case '[':
					case ']':
					case '.':
					case ',':
					case ':':
						$this->pos++;
						return new Token(ord($char), null);
						break;
				}
			}
			$this->pos++;
		}
		return false;
	}

	/**
	 * Return True if has token
	 * @return bool
	 */
	function hasTokens(){
		return isset($this->str[$this->pos]);
	}

	function str($start){
		$buffer = '';
		$escape = false;
		$this->pos++;
		while($this->hasTokens()){
			$char = $this->str[$this->pos];
			if($char == $start && !$escape){
				$this->pos++; /*quita la comilla de cierre*/
				break;//salgo fin de la cadena
			}
			switch ($char){
				case '\\':
					$escape = true;
				break;
				default:
					$escape = false;
			}
			$buffer .= $char;
			$this->pos++;
		}
		return new Token(Token::T_STRING, "'$buffer'");
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
		$next = $this->nextToken();
		if($next && $next->is(Token::T_O_CORCH)){
			$next = $this->nextToken();
			$word.= '['; /*añado el corchete*/
			if($next  && $next->is(Token::T_IDENT) ||
			 	$next->is(Token::T_STRING) ||
			  	$next->is(Token::T_NUMBER)){
				if($next->is(Token::T_IDENT))
					$word.= '$';
				$word.= $next->getValue();
				$next = $this->nextToken();
				if(!$next || !$next->is(Token::T_C_CORCH)){
					throw new ParseException($this->node, 'Expecting ]');
				}
				return  new Token(Token::T_IDENT, "$word]");
			}else{
				var_dump($next);
				throw new ParseException($this->node, 'Expenting Identifiquer');
			}
		}else{
			if($next)
				$this->pos -= $next->length() + 1;
			return  new Token(Token::T_IDENT, $word);
		}
	}

	public function getTokens(){
		while (($t = $this->nextToken())) {
			$this->tokens[] = $t;
		}
		if (empty($this->tokens)){
			var_dump($this->node);
			die;
		}
		return $this->tokens;
	}

	static function ctype_alphadash($char){
		return ctype_alpha($char) || $char == '_';
	}
	
}