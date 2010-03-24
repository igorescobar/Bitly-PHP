
<h1>Encurtando URL's</h1>

<?php

	include_once("bitly.php");
	
	$bitly 		= new Bitly();
	$bitly->url = 'http://www.google.com/';
	$bitly->shorten();
	echo $bitly->getData()->shortUrl . '<br />';
	echo $bitly->getData()->userHash;
	
?>

<h1>Acessando a URL Expandida</h1>

<?php
	$bitly 		= new Bitly();
	$bitly->url = 'http://bit.ly/b6R4Uf';
	$bitly->expand();
	
	echo $bitly->getData()->longUrl;
?>

<h1>Acessando as informações de uma URL encurtada</h1>

<?php
	$bitly 		= new Bitly();
	$bitly->url = 'http://bit.ly/b6R4Uf';
	$bitly->info();
	
	echo $bitly->getData()->thumbnail->medium;
?>

<h1>Acessando as estatísticas de uma URL encurtada</h1>

<?php
	$bitly 		= new Bitly();
	$bitly->url = 'http://bit.ly/b6R4Uf';
	$bitly->stats();
	echo $bitly->getData()->clicks;
?>
