<?php
namespace KTemplate;
class Sentence {

	const RE_FOR = '/for\s(\w+)(?:,\s+(\w+))?\sin\s(\w+)/';

	const RE_INLINE = '/^\s*inline\s+(\'|")([\w.\n]+)\1\s*$/';

	const RE_BLOCK = '/^block (\w+)$/';

	const RE_EXTENDS = '/^\s*extends\s+(\'|")([\w.\n]+)\1\s*$/';

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
		} else {
			$block = substr($cut[0], 3);
			$comp->removeNested($block);
			if ($cut[0] != 'endblock') {
				$this->code = '<?php } ?>';
			} else {
				$this->comp->setCurrent('main');

			}

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
			return sprintf('<?php foreach($%s as $%s  => $%s){ ?>', $match[3], $match[1], $match[2]);
		}
		return sprintf('<?php foreach($%s as $%s){ ?>', $match[3], $match[1]);

	}

	function code_if() {
		$this->comp->addNested('if');
		$part = explode(' ', $this->text);
		$node = new Node\ExecNode(0);
		$node->addContent(substr($this->text, 3));
		$part = $node->stack();
		$text = '';
		foreach ($part as $key) {
			$text .= (string) $key;
		}
		return "<?php if($text){ ?>";
	}

	function code_else() {
		return '<?php }else{ ?>';
	}

	function code_inline() {
		if (0 == preg_match(self::RE_INLINE, $this->text, $match)) {
			throw new ParseException($this->line, $str);
		}
		$tpl = $this->comp->getTpl();
		$comp = new Compiler($tpl, $match[2]);
		return $comp->generate();
	}

	function code_block() {
		if (0 == preg_match(self::RE_BLOCK, $this->text, $match)) {
			throw new ParseException($this->line, $str);
		}
		$this->comp->addNested('block');
		$this->comp->setCurrent($match[1]);
		return "<?php static::{$match[1]}(\$vars);?>";
	}

	function code_extends() {
		if (0 == preg_match(self::RE_EXTENDS, $this->text, $match)) {
			throw new ParseException($this->line, $str);
		}
		$tpl = $this->comp->getTpl();
		$this->comp->setParent($tpl->load($match[2]));
	}
}