<?php
	require '../config.php';
	require '../lib/get_new_id.php';

	$new_id = get_new_id();
	echo $new_id . '<br />';
	if (strlen($new_id) === 32)
		echo 'ID length test passed. <br />';
?>