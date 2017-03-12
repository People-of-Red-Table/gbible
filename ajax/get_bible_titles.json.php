<?php
	require "../config.php";
	$language_name = $_REQUEST['language'];

	$statement_language = $links['sofia']['pdo'] -> prepare('select code from iso_639_languages where language_name = :language_name');
	$result_language = $statement_language -> execute(array('language_name' => $language_name));

	if (!$result_language)
		log_msg(__FILE__ . ':' . __LINE__ . ' Languages PDO exception. ' . json_encode($statement_language -> errorInfo()) . ', $_REQUEST = {' . json_encode($_REQUEST) . '}');

	$language_row = $statement_language -> fetch();

	setcookie('language', $language_row['code'], time() + 30 * 24 * 3600);	

	$statement = $links['sofia']['pdo'] -> prepare(
							'select b_code, title from b_shelf where language = :language_name order by title');
						$result_books = $statement -> execute(array('language_name' => $language_name));

	if (!$result_books)
	{
		log_msg(__FILE__ . ':' . __LINE__ . ' Bible PDO exception. ' . json_encode($statement -> errorInfo()) . ', $_REQUEST = {' . json_encode($_REQUEST) . '}');
		//echo "Whoops. We've got issue with PDO connection... Sorry. Please contact support. "
		;
		//print_r($links['sofia']['pdo']);

	}
	else
	{
		echo json_encode($statement -> fetchAll());
	}
?>