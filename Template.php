<?php
namespace KTemplate;

class Template {

	/**
	 * Configuration of template engine
	 * @var array
	 */
	protected $config = array(
		'use_cache' => false,
	);

	protected $tpl_file = '';

	protected $compilated = '';

	function __construct(Array $c = null) {
		if ($c) {
			$this->config = array_merge($this->config, $c);
		}

	}

	function load($name, $var) {
		$this->tpl_file = "{$this->config['template_dir']}/$name";
		$id = hash('sha256', $this->tpl_file);
		$this->compilated = "{$this->config['cache_dir']}/$id.php";
		self::generate($id, $var);
		include $this->compilated;
		$fun = "_$id";
		$fun($var);
	}

	function generate($id, $var) {
		if (!is_file($this->compilated) ||
			!$this->config['use_cache'] ||
			(filemtime($this->tpl_file) > filemtime($this->compilated))) {
			$parse = new Compiler($this->tpl_file, $var);
			$parse->generate($this->compilated, $id);
		}
	}
}