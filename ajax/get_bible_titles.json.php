<?php
	require "../config.php";
	$language_name = $_GET['language'];
	$statement = $links['sofia']['pdo'] -> prepare(
							'select b_code, title from b_shelf where language = :language_name order by title');
						$result_books = $statement -> execute(array('language_name' => $language_name));

	if (!$result_books)
	{
		echo "Whoops. We've got issue with PDO connection... Sorry. Please contact support. "
		;
		print_r($links['sofia']['pdo']);

	}
	else
	{
		echo json_encode($statement -> fetchAll());
	}
?>