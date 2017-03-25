<?php
	session_start();
	require 'log.php';
	require 'config.php';
	require 'lib.php';
	require './obj/objects.php';
	require 'language.php';
	require './users/init.php';
	$file = @fopen('./logs/visits.txt', 'a');
	@fwrite($file, $country.';'.$language.';'. (new DateTime()) -> format("Y-m-d H:i:s"));
	@fclose($file);
	require 'charity.php';
	require 'header.php';
	require 'content.php';
	require 'footer.php';
	require 'free.php';
?>