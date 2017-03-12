<?php
	echo 'Sendmail test <br/>';
	$result = mail('angel_of_the_light@zoho.com', 'mail test', 'Did you get it?');
	var_dump($result);
?>