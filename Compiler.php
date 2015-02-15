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

	/**
	 * Current block
	 * @var string
	 */
	protected $current = 'main';

	protected $file = '';

	protected $compilated = '';

	protected $parent = NULL;

	protected $tpl = NULL;

	protected $id = NULL;

	function __construct(Template $tpl, $file) {
		$this->tpl = $tpl;
		$this->file = $tpl->resolverFileName($file);
	}

	function generate() {
		$a = file($this->file);
		$this->block[$this->current] = '';
		$this->line = 0;
		foreach ($a as $line) {
			$this->line++;
			$this->block[$this->current] .= preg_replace_callback(static::INTERPOLATE, array($this, 'callback'), $line);
		}
	}

	function getBlocks() {
		return $this->block;
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

	function callback($match) {
		$typeArray = array('{' => 'variable', '%' => 'execution', '#' => 'comment');
		/*Only 1 and 3 are important*/
		$type = $typeArray[$match[1]];
		return $this->$type(trim($match[3]));
	}

	function comment($val) {
		return "<?php  /*{$val}*/ ?>";
	}

	function execution($val) {
		$sent = new Sentence($val, $this);
		return $sent->code();
	}

	function variable($val) {
		$list = explode('|', $val);
		$var = trim(array_shift($list));
		if ($var[0] == '\'') {
			return "<?php echo $var ?>";
		}
		return "<?php echo \$$var ?>";
	}

	/**
	 * Thow a parse Exception
	 * @param string $str message for exception
	 */
	function parseError($str) {
		return new ParseException($this->line, $str);
	}

	function getTpl() {
		return $this->tpl;
	}

	function getFile() {
		return $this->file;
	}

	function setCurrent($c) {
		if (isset($this->block[$c])) {
			$this->parseError("Can't rewrite block $c");
		}
		$this->block[$c] = '';
		$this->current = $c;

	}

	function getParent() {
		return $this->parent;
	}

	function setParent($name) {
		$this->parent = $name;
	}
}