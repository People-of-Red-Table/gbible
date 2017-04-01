<?php
	
	function display_message($message)
	{
		echo '<p class="alert alert-' . $message['type'] . '">' . $message['message'] . '</p>';
	}

	function display_messages($messages)
	{
		foreach ($messages as $message) 
		{
			display_message($message);
		}
	}
	
?>