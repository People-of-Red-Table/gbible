<?php
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	function open_connection($type, $base)
	{
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
		}
		else
		{
			$db['gbible']['host'] = 'localhost';
			$db['gbible']['name'] = 'gbible';
			$db['gbible']['user'] = 'root';
			$db['gbible']['password'] = '';

			$db['sofia']['host'] = '50.62.209.41';
			$db['sofia']['name'] = 'sofia';
			$db['sofia']['user'] = 'php';
			$db['sofia']['password'] = 'Tirh73~3';

			$db['port']['host'] = '184.168.47.17';
			$db['port']['name'] = 'port_17';
			$db['port']['user'] = 'u_port_17';
			$db['port']['password'] = 'T7jb0%h7';
		}
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
				echo "Whoops. We've got issue with MySQL connection for `$base`.";
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
				echo "Whoops. We've got issue with PDO connection for `$base`.";
				return false;
			}
		}
	}

	$links['gbible']['pdo'] = open_connection('pdo', 'gbible');
	$links['gbible']['mysql'] = open_connection('mysql', 'gbible');
	$links['sofia']['pdo'] = open_connection('pdo', 'sofia');	
	$links['sofia']['mysql'] = open_connection('mysql', 'sofia');

?>