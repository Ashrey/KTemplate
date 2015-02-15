<?php
namespace KTemplate;
class Sentence {

	const RE_FOR = '/for\s(\w+)(?:,\s+(\w+))?\sin\s(\w+)/';

	const RE_IF = '/for\s(\w+)(?:,\s+(\w+))?\sin\s(\w+)/';

	/**
	 * Text of sentence
	 * @var string
	 */
	protected $text = '';

	/**
	 * Compiler object
	 * @var Compiler
	 */
	protected $comp = 0;

	/**
	 * PHP code
	 * @var string
	 */
	protected $code = '';

	function __construct($text, Compiler $comp) {
		$this->comp = $comp;
		$this->text = $text;
		$cut = explode(' ', $text);
		/*Is it a end sentence?*/
		if (strncmp('end', $cut[0], 3) !== 0) {
			$cb = "code_{$cut[0]}";
			if (method_exists($this, $cb)) {
				$this->code = $this->$cb();
			}
			var_dump($cut);
		} else {
			$block = substr($cut[0], 3);
			$comp->removeNested($block);
			$this->code = '}';
		}

	}

	function code() {
		return $this->code;
	}

	function code_for() {
		if (0 == preg_match(self::RE_FOR, $this->text, $match)) {
			throw new ParseException($this->line, $str);
		}
		$this->comp->addNested('for');
		if (!empty($match[2])) {
			return sprintf('foreach($%s as $%s  => $%s){', $match[3], $match[1], $match[2]);
		}
		return sprintf('foreach($%s as $%s){', $match[3], $match[1]);

	}

	function code_if() {
		$this->comp->addNested('if');
		$part = explode(' ', $this->text);
		var_dump($part);
		return 'if(true){';
	}

	function code_else() {
		return '}else{';
	}

}