<?php

	function set_user_timezone($datetime)
	{
		$user_date = new DateTime($datetime -> format('Y-m-d H:i:s'));
		$timezone = new DateTimezone($_SESSION['timezone']);
		$user_date -> setTimezone($timezone);
		return $user_date;
	}
	
?>