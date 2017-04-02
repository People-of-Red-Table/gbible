<?php

	$date = new DateTime();
	$to_date = new DateTime($date -> format('Y-m-d'));

	$to_date->add(new DateInterval("P1Y"));
	$to_date->sub(new DateInterval("P1D"));
	
	if ($date < $to_date)
	{
		echo 'Date comparison is okay.';
	}
	else
	{
		echo 'Exception!..';
	}


?>