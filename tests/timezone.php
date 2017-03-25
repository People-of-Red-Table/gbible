<?php

	function format_date($datetime)
	{
		$date = new DateTime($datetime);
		$timezone = new DateTimezone('Europe/London'); // $_SESSION['timezone']
		$date -> setTimezone($timezone);
		return $date -> format('H:i:s d.m.Y');
	}

	function set_user_timezone($datetime)
	{
		$user_date = new DateTime($datetime -> format('Y-m-d H:i:s'));
		$timezone = new DateTimezone('Europe/London'); // $_SESSION['timezone']
		$user_date -> setTimezone($timezone);
		return $user_date;
	}

	$date = new DateTime();
	echo  '<b>format_date</b>: server`s time: ' . $date -> format("Y-m-d H:i:s") . ', <br /> Loc. time [for Europe/London]: ' . format_date($date -> format('Y-m-d H:i:s'));

	echo  '<br/><br/><b>set_user_timezone</b>: server`s time: ' . $date -> format("Y-m-d H:i:s") 
			. ', <br /> Loc. time [for Europe/London]: ' . set_user_timezone($date) -> format("Y-m-d H:i:s");
?>