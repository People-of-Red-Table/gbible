<?php
	// type your settings and rename this file to 'config.php'
	// or just copy to  'config.php' and edit that file.


	if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1')
	{
		$db['gbible']['host'] = 'localhost';
		$db['gbible']['name'] = 'gbible';
		$db['gbible']['user'] = 'root';
		$db['gbible']['password'] = '';

		$db['sofia']['host'] = 'localhost';
		$db['sofia']['name'] = 'sofia';
		$db['sofia']['user'] = 'root';
		$db['sofia']['password'] = '';

		$db['port']['host'] = 'localhost';
		$db['port']['name'] = 'port';
		$db['port']['user'] = 'root';
		$db['port']['password'] = '';
		ini_set('display_errors', 1);
		ini_set('html_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);
	}
	else
	{
		$db['gbible']['host'] = 'mysql.hostinger.co.uk';
		$db['gbible']['name'] = 'bible';
		$db['gbible']['user'] = 'root';
		$db['gbible']['password'] = '';

		$db['sofia']['host'] = 'mysql.hostinger.co.uk';
		$db['sofia']['name'] = 'sofia';
		$db['sofia']['user'] = 'root';
		$db['sofia']['password'] = '';

		$db['port']['host'] = '184.168.47.17';
		$db['port']['name'] = 'port';
		$db['port']['user'] = 'root';
		$db['port']['password'] = '';

		ini_set('display_errors', 0);
		ini_set('display_startup_errors', 0);
		error_reporting(0);
	}

	function open_connection($db, $type, $base)
	{
		if ($type == 'mysql')
		{
			$link = mysqli_connect($db[$base]['host'], $db[$base]['user'], 
									$db[$base]['password'], $db[$base]['name']);
			if($link)
			{
				mysqli_set_charset($link, 'utf8');
				return $link;
			}
			else
			{
				log_msg(__FILE__ . ':' . __LINE__ . " We've got issue with SQL connection for `$base`. Info = {" . json_encode(mysql_error()));
				return false;
			}
		}
		else
		{
			$link =  new PDO('mysql:host=' . $db[$base]['host'] . ';dbname=' . $db[$base][
			'name'] , $db[$base]['user'], $db[$base]['password'], 
			array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			if($link)
				return $link;
			else
			{
				log_msg(__FILE__ . ':' . __LINE__ . " We've got issue with PDO connection for `$base`. Info = {" . json_encode($link -> errorInfo()));
				return false;
			}
		}
	}

	//$links['gbible']['pdo'] = open_connection($db, 'pdo', 'gbible');
	//$links['gbible']['mysql'] = open_connection($db, 'mysql', 'gbible');
	$links['sofia']['pdo'] = open_connection($db, 'pdo', 'sofia');	
	$links['sofia']['mysql'] = open_connection($db, 'mysql', 'sofia');

?>