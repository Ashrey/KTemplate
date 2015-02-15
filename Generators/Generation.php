<?php
namespace KTemplate\Generators;
use \KTemplate\Compiler;

class Generation {

	protected $c;

	protected $output;

	function __construct(Compiler $c, $out, $id) {
		$this->c = $c;
		$this->tpl = $c->getTpl();
		$this->id = $id;
		$this->output = new Output($out);
	}

	function init() {
		$name = "Tpl{$this->id}";
		$output = $this->output;
		$output->writeline('<?php');
		$output->writeline('/*generation*/');
		$parent = $this->c->getParent();
		if (!empty($parent)) {
			list($id, $compiled) = $parent;
			$output->writeline("include '$compiled';");
		}
		$output->write("class $name");
		if (isset($id)) {
			$output->write(" extends Tpl{$id}");
		}

		$output->writeline('{');
		$output->indUp();
		foreach ($this->c->getBlocks() as $key => $value) {
			$output->writeline("static function $key(\$vars){");
			$output->indUp();
			$output->writeline('extract($vars);?>');
			$output->writeline($value);
			$output->writeline('<?php } ');
			$output->indDown();
		}
		$output->writeline(' } ?>');
	}

}