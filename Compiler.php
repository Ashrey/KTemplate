<?php
namespace KTemplate;
class Compiler {
	/**
	 * Interpolation regular expression
	 */
	const INTERPOLATE = '/\{([#%]|({))(.+)(?(2)\}|\1)}/U';

	const RE_VAR = '/^\w+(?:\[("|\')\w+\1\])?(?:\.(\w+))?$/';

	/**
	 * Number of currente line
	 * @var integer
	 */
	protected $line = 0;

	protected $nested = array();

	protected $block = array();

	function __construct() {

	}

	function addNested($val) {
		$this->nested[] = $val;
	}

	function removeNested($val) {
		$last = array_pop($this->nested);
		if ($last != $val) {
			throw $this->parseError("You closed $val and last opened was $last");
		}
	}

	function parse($file) {
		$a = file($file);
		$str = '';
		$this->line = 0;
		foreach ($a as $line) {
			$this->line++;
			$str .= preg_replace_callback(static::INTERPOLATE, array($this, 'callback'), $line);
		}
		file_put_contents('tmp/exec.php', $str);
	}

	function go($var) {
		extract($var);
		include 'tmp/exec.php';
	}

	function callback($match) {
		$typeArray = array('{' => 'variable', '%' => 'execution', '#' => 'comment');
		/*Only 1 and 3 are important*/
		$type = $typeArray[$match[1]];
		return '<?php ' . $this->$type(trim($match[3])) . ' ?>';
	}

	function comment($val) {
		return "/*{$val}*/";
	}

	function execution($val) {
		$sent = new Sentence($val, $this);
		return $sent->code();
	}

	function variable($val) {
		$list = explode('|', $val);
		$var = trim(array_shift($list));
		if ($var[0] == '\'') {
			return "echo $var";
		}
		return "echo \$$var";
	}

	/**
	 * Thow a parse Exception
	 * @param string $str message for exception
	 */
	function parseError($str) {
		return new ParseException($this->line, $str);
	}
}