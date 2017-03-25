<?php
	$date = new DateTime();
	echo 'Today is ' . $date -> format('D d.m.Y') . '<br />';
	$interval = new DateInterval('P1D');
	$date -> add($interval);
	echo 'Tomorrow is ' . $date -> format('D d.m.Y') . '<br />';
?>