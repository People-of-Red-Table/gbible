<?php
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);


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
		$db['gbible']['host'] = 'mysql.hostinger.co.uk';
		$db['gbible']['name'] = 'u601102806_bible';
		$db['gbible']['user'] = 'u601102806_phpb';
		$db['gbible']['password'] = 'WMpXjqw1aFFy';

		$db['sofia']['host'] = 'mysql.hostinger.co.uk';
		$db['sofia']['name'] = 'u601102806_sofia';
		$db['sofia']['user'] = 'u601102806_php';
		$db['sofia']['password'] = 'VOX7UtZa939H';

		$db['port']['host'] = '184.168.47.17';
		$db['port']['name'] = 'port_17';
		$db['port']['user'] = 'u_port_17';
		$db['port']['password'] = 'T7jb0%h7';
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

	$links['gbible']['pdo'] = open_connection($db, 'pdo', 'gbible');
	$links['gbible']['mysql'] = open_connection($db, 'mysql', 'gbible');
	$links['sofia']['pdo'] = open_connection($db, 'pdo', 'sofia');	
	$links['sofia']['mysql'] = open_connection($db, 'mysql', 'sofia');

?>