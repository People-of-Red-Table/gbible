<?php
	session_start();
	require 'log.php';
	require 'config.php';
	require 'lib.php';
	require './obj/objects.php';
	require 'language.php';
	$file = @fopen('./logs/visits.txt', 'a');
	@fwrite($file, $country.';'.$language.';'. (new DateTime()) -> format("Y-m-d H:i:s") . ';' . $_SERVER['REMOTE_ADDR'] . ';' . $_SERVER['QUERY_STRING'] . PHP_EOL);
	@fclose($file);
	require './users/init.php';
	require 'charity.php';
	require 'header.php';
	require 'content.php';
	require 'footer.php';
	require 'free.php';
?>