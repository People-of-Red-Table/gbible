<?php
	
	if (isset($_SERVER['REMOTE_ADDR']))
	{
		echo 'What are you doing here, genius? Trying to help me to install Bible Site? =]<br />';
		echo 'You shall not run install via web. <br />';
		exit;
	}
	else
	{
		$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
		require '../config.php';
	}
	// All in One is coming =]

	echo 'Read ../INSTALL.txt =]' . PHP_EOL;
?>