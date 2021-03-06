<?php
namespace KTemplate\Generators;
use \KTemplate\Compiler;

class Generation {

    /**
     * Compiler object
     * @var Compiler
     */
	protected $c;


    /**
     * Output object
     * @var Output
     */
	protected $output;

    /**
     * Id hash of class
     * @var string
     */
    protected $id;

	function __construct(Compiler $c, $out, $id) {
		$this->c = $c;
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
			/*Main block in child template*/
			if ($key == 'main' && isset($id)) {
				continue;
			}

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