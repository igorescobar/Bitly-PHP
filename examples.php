<?php
include_once("bitly.php");

$bitly 		= new Bitly();
$bitly->url = 'http://www.google.com/';
echo $bitly->getShortUrl();
echo $bitly->getUserHash();

?>