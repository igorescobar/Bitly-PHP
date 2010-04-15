<?php

	include_once("../bitly.php");
	
	$bitly = new Bitly('<your_login>','<your_api_key>');
	echo $bitly->shorten('http://www.google.com/'); // shortcut to print the shorten url
	
	// Detailed return
	
	print_r ( $bitly->getData () );
	
?>