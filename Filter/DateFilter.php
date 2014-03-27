<?php
namespace KTemplate\Filter;
class DateFilter extends Filter{
    function generate($what, Array $param){
        return sprintf('date(%s,%s)',  $param[0], $what);
    }
}