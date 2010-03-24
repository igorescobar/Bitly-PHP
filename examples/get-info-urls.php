<?php
	
	include_once("../bitly.php");
	
	$bitly 		= new Bitly();
	$bitly->url = 'http://bit.ly/b6R4Uf';
	$bitly->info();
	
	echo $bitly->getData()->thumbnail->medium;
?>