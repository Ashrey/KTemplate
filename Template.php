<?php
namespace KTemplate;
use KTemplate\Generators\Generation;

class Template {

	/**
	 * Configuration of template engine
	 * @var array
	 */
	protected $config = array(
		'use_cache' => false,
		'template_dir' => '',
	);

	protected $tplfile;

	function __construct(Array $c = null) {
		if ($c) {
			$this->config = array_merge($this->config, $c);
		}
	}

	function load($file) {
		$parse = new Compiler($this, $file);
		$id = $this->getHash($file);
		$compilated = "{$this->config['cache_dir']}/$id.php";
		if (!is_file($compilated) ||
			!$this->config['use_cache'] ||
			(filemtime($tplfile) > filemtime($compilated))) {
			$parse->generate();
			$gen = new Generation($parse, $compilated, $id);
			$gen->init();
		}
		return array($id, $compilated);
	}

	function render($name, $var) {
		list($id, $compilated) = $this->load($name, $var);
		include $compilated;
		$fun = "Tpl$id";
		$fun::main($var);
	}

	function getHash($name) {
		return hash('sha256', $name);
	}

	function resolverFileName($name) {
		return "{$this->config['template_dir']}/$name";
	}

}