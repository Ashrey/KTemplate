<?php
require "autoload.php";
$config = array(
    'cache_dir' => 'tmp/',
    'template_dir' => 'tpl/',
);

\KTemplate\Template::Configure($config);

$people =  array(
    array('first_name' => 'George </br>', 'last_name' => 'Bush', 'gender' => 'Male', 'foobar' => 'extra'),
    array('first_name' => 'Bill', 'last_name' => 'Clinton', 'gender' => 'Male'),
    array('first_name' => 'Margaret', 'last_name' => 'Thatcher', 'gender' => 'Female'),
    array('first_name' => 'Condoleezza', 'last_name' => 'Rice', 'gender' => 'Female'),
    array('first_name' => 'Pat', 'last_name' => 'Smith', 'gender' => 'Unknown', 'bar' => 'foo'),
    array('first_name' => '"Cesar', 'last_name' => 'Rodas"', 'gender' => 'Male'),
);
$vars = array(
    'some_list' => array(1, 2, 3, 4, 4, 4, 5),
    'title' => 'crodas',
    'base_template' => 'subtemplate.html',
    'people' => $people,
    'days' => array(
        strtotime("01/27/2010"),
        strtotime("01/28/2010"),
        strtotime("02/22/2010"),
        strtotime("02/28/2010"),
        strtotime("08/25/2010"),
        strtotime("08/30/2010"),
    ),
    'templates' => array('base' => 'index-test.html'),
);


$time = microtime(TRUE);
\KTemplate\Template::load('index.html', $vars);
$total = microtime(TRUE)-$time; 
$mem = memory_get_usage();

var_dump(array('memory' => (memory_get_usage()-$mem)/(1024*1024), 'seconds' => $total));
