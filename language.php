<?php

	if (isset($_REQUEST['language']) and 
		isset($_REQUEST['country']))
	{
		$language = $_REQUEST['language'];
		$country = $_REQUEST['country'];
		$language_country = $language . '-' . $country;
	}	
	elseif (isset($_COOKIES['language']) and 
		isset($_COOKIES['country']))
	{
		$language = $_COOKIES['language'];
		$country = $_COOKIES['country'];
		$language_country = $language . '-' . $country;
	}
	else
	{
		$array = explode(';', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$array = explode(',', $array[0]);
		$language = strtolower($array[1]);
		$language_country = strtolower($array[0]);
		$country = explode('-', $language_country)[1];
	}
/*
	// Code has got issue with PDO
	$statement = $links['sofia']['pdo'] -> prepare('select language_code from iso_ms_languages where lower(code) = :language_code');
	$result = $statement -> execute(array('language_code' => $language_country));

	if (!$result)
	{
		echo "Whoops. We've got issue with PDO connection... Sorry. Please contact support.";
		print_r($links['sofia']['pdo']);
		print_r($links['sofia']['pdo'] -> errorInfo());
		print_r($result);
	}

	if ($statement -> rowCount() === 0)
	{
		//echo "That's very unusual `Accept Language`, you know. I cut you off.";
		exit;
	}

	if (file_exists('./languages/' . $language_country . '.php'))
	{
		require './languages/' . $language_country . '.php';
	}
*/
?>