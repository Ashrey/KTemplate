<?php
namespace KTemplate;

class Template{
	protected static $config;

	static function configure($c){
		self::$config = $c;
	}

	static function load($name, $var){
		$tpl      = self::$config['template_dir']. "/$name";
		$id = hash('sha256', $tpl);
		$compile  = self::$config['cache_dir'] . "/$id.php";
		self::generate($tpl, $id, $var);
	}

	static function generate($file, $id, $var){
		$parse  = new Parse($file, $var);
		$gen = new Generate($parse->getNodes(), $id, $var);
		$gen->generate();
	}
}