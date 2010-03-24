<?php

	include_once("../bitly.php");
	
	$bitly = new Bitly();
	$bitly->url = 'http://www.google.com/';
	$bitly->shorten();
	echo $bitly->getData()->shortUrl . '<br />';
	echo $bitly->getData()->userHash;
	
?>