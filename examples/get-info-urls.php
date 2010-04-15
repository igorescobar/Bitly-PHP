<?php
	
	include_once("../bitly.php");
	
	$bitly = new Bitly('<your_login>','<your_api_key>');
	print_r ( $bitly->info ( 'http://bit.ly/b6R4Uf' ) );

?>