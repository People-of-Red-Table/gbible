<?php

	require 'config.php';
	$result = mysqli_query($links['sofia']['mysql'], 
				'select b_code, table_name, title from b_shelf');

	while ($shelf_row = mysqli_fetch_assoc($links['sofia']['mysql']))
	{

	}

?>