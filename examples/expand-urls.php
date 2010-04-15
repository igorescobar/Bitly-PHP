<?php
	
	include_once("../bitly.php");
	
	$bitly = new Bitly('<your_login>','<your_api_key>');
	echo $bitly->expand('http://bit.ly/b6R4Uf'); // shortcut to print the long url
	
	// Detailed return
	
	print_r ( $bitly->getData () );

?>