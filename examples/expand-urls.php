<?php
	
	include_once("../bitly.php");
	
	$bitly 		= new Bitly();
	$bitly->url = 'http://bit.ly/b6R4Uf';
	$bitly->expand();
	
	echo $bitly->getData()->longUrl;
?>