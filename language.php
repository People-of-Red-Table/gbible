<?php

	require './languages/language_array.php';

	if (isset($_REQUEST['country']))
	{
		$country = $_REQUEST['country'];
	}

	if (isset($_REQUEST['language']))
	{
		$language = $_REQUEST['language'];
		//log_msg(__FILE__ . ':' . __LINE__ . " \$country = $country");
		//log_msg(__FILE__ . ':' . __LINE__ . " \$language = $language");
	}

	if (isset($_REQUEST['language']) and 
		isset($_REQUEST['country']))
	{
		$language_country = $language . '-' . $country;
	}	
	/*elseif (isset($_COOKIES['language']) and 
		isset($_COOKIES['country']))
	{
		$language = $_COOKIES['language'];
		$country = $_COOKIES['country'];
		$language_country = $language . '-' . $country;
	}*/
	$array = explode(';', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
	$array = explode(',', $array[0]);
	if (!isset($language))
		$language = strtolower($array[1]);
	if (!isset($language_country))
		$language_country = strtolower($array[0]);
	if (!isset($country))
		$country = explode('-', $language_country)[1];

	$hal_language_country = strtolower($array[0]);
	$hal_country = explode('-', $hal_language_country)[1];
	$hal_language = strtolower($array[1]);
	$fb_language_country = str_replace('-', '_', $hal_language_country);

	require './languages/en.php';

	$interface_language = strtolower($hal_language_country);


	if (isset($_SESSION['interface_language']))
	{
		$interface_language = $_SESSION['interface_language'];
	}

	if (isset($_REQUEST['interface_language']))
	{
		$interface_language = $_REQUEST['interface_language'];
		setcookie('interface_language', $_REQUEST['interface_language'], 30 * 24 * 3600);
		$_SESSION['interface_language'] = $_REQUEST['interface_language'];
	}

	$lang_path = './languages/' . $interface_language . '.php';

	if (file_exists($lang_path)
		and in_array($interface_language, $languages))
		require $lang_path;
	else
	{
		if (stripos($interface_language, '-') !== FALSE)
		{
			$interface_language = explode('-', $interface_language)[0];
			$lang_path = './languages/' . $interface_language . '.php';					
			if (file_exists($lang_path)
			and in_array($interface_language, $languages))
			require $lang_path;
		}
	}

	$charity_country = $hal_country;

	if (strlen($country) > 2)
	{
		$statement_country = $links['sofia']['pdo'] -> prepare('
							select country_code from iso_ms_languages
							where country_name = :country_name
						');
		$country_result = $statement_country -> execute(array('country_name' => $country));
		if(!$country_result)
			log_msg(__FILE__ . ':' . __LINE__ . ' Countries PDO query exception. Info = {' . json_encode($statement_country -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');

		$country_row = $statement_country -> fetch();

		$country = $country_row['country_code'];
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