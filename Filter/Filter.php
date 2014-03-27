<?php
namespace KTemplate\Filter;
use KTemplate\Token;

abstract class Filter{
    abstract function generate($what, Array $param);
}