<?php

	$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
	require '../config.php';
	$link = $links['sofia']['mysql'];
	mysqli_set_charset($link,'utf8');
	if (!$link) { echo mysqli_connect_error(); exit;};

	$log = fopen('log.txt', 'w');

	$queries = file('prepare.sql');
	$counter = 0;
	$done = false;		
	while ($done === false)
	{
		$counter ++;
		if(!$link)
		{
					fwrite($log, 'Reconnect.' . PHP_EOL);
					$link = open_connection($db, 'mysql', 'sofia');
					mysqli_set_charset($link,'utf8');
					if (!$link) { fwrite($log, 'prepare.sql, line ' . $counter . ';' . mysqli_connect_error()); };
		}
		$result = true;
		foreach ($queries as $query) 
		{
			if (strlen($query) > 5)
			{
				try 
				{
					$result = mysqli_query($link, $query);					
				} 
				catch (Exception $e) 
				{
					if (!$result) { fwrite($log, 'prepare.sql, line ' . $counter . ';' . PHP_EOL . '`' . $query . '` ' 
						. PHP_EOL . mysqli_error($link));}
					$result = false;
					break;					
				}
			}
		}
		if($result === true)
			$done = true;
	}


	mysqli_close($link);

	fclose($log);

?>