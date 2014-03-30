<?php
namespace KTemplate;
use KTemplate\Generators\Output;

class Template{
    protected static $config = array(
        'check_cache' => false,
    );

    static function configure($c){
        self::$config = array_merge(self::$config, $c);
    }

    static function load($name, $var){
        $tpl = self::$config['template_dir']. "/$name";
        $id  = hash('sha256', $tpl);
        $compile  = self::$config['cache_dir'] . "/$id.php";
        self::generate($tpl, $id, $var, $compile);
        include $compile;
        $fun = "_$id";
        $fun($var);
    }

    static function generate($file, $id, $var, $compile){
        if (!is_file($compile) || !self::$config['check_cache'] || (filemtime($file) > filemtime($compile))){
            $parse  = new Parse($file, $var);
            $n = $parse->getNodes();
            $gen = new Generate($n, $id, $var, new Output($compile));
            $gen->generate();
        }
    }
}