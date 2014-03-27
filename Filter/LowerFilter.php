<?php
namespace KTemplate\Filter;

class LowerFilter extends Filter{
    function generate($what, Array $param){
        return sprintf('strtolower(%s)', $what);
    }
}