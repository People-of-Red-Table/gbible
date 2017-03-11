<?php
	echo 'Sendmail test <br/>';
	$result = mail('test-mail@zoho.com', 'mail test', 'Did you get it?');
	var_dump($result);
?>