<?php
namespace KTemplate\Generators\Tag;
use KTemplate\Token;
use KTemplate\Parse;
class InlineTag extends \KTemplate\Generators\Generators{

    function generate(){
        $conf = \KTemplate\Template::configure();
        $tmpname = $conf['cache_dir'] . '/'. uniqid();
        $t1  =  $this->nextRequire(Token::T_STRING);
        $parse  = new Parse($conf['template_dir'] .'' .$t1->getValue(), array());
        $parse->generate($tmpname);
        $this->write(file_get_contents($tmpname));
        $this->end();
    }
}