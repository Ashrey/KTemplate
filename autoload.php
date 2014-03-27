<?php
spl_autoload_register(function($name){
	$corto = explode('\\', $name);
	$ns = array_shift($corto);
	if($ns == 'KTemplate'){
		$file = implode(DIRECTORY_SEPARATOR , $corto);
		$path = __DIR__ . DIRECTORY_SEPARATOR . "$file.php";
		if(is_readable($path))
			require $path;
	}
});