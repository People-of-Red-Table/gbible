<?php
	setcookie(session_name(), session_id(), time() + 30 * 24 * 3600);

	require 'auth.php';

	// !! 'country', 'language', user settings? 
	$auto_save = array('country', 'language', 'b_code', 'book_index', 'chapter_index', 'book', 'chapter');

	foreach ($auto_save as $item)
	{
		if(isset($_REQUEST[$item]))
		{
			$$item = $_REQUEST[$item];
			/*if (isset($_GET[$item]))
				setcookie($item, $_GET[$item], time() + 30 * 24 * 3600);
			if (isset($_POST[$item]))
				setcookie($item, $_POST[$item], time() + 30 * 24 * 3600);
			*/

		}
	}

	if (isset($_REQUEST['charity_country']))
		$charity_country = $_REQUEST['charity_country'];

	if (stripos( $language, '-' ) !== FALSE)
	{
		$array = explode('-', $language);
		$country = $array[1];
		$language = $array[0];
	}

	foreach (array('verse_index', 'verse') as $item)
	{
		if(isset($_REQUEST[$item]))
		{
			$$item = $_REQUEST[$item];
		}
	}

	if (!isset($verse))
		$verse = 0;

	if (!isset($b_code))
	{
		$statement_bibles = $links['sofia']['pdo'] -> prepare(
											'select b_code from b_shelf where language in (
											
											select language_name from iso_ms_languages where lower(language_code) = :language and language_name in (select distinct language from b_shelf))

											union
											select b_code from b_shelf where language in (
											select language_name from iso_ms_languages where language_name = :language and language_name in (select distinct language from b_shelf))

											union
											select b_code from b_shelf where language in (
											select language_name from iso_ms_languages where country_name = :country  and language_name in (select distinct language from b_shelf))

											union
											select b_code from b_shelf where language in (
											select language_name from iso_ms_languages where country_code = :country and language_name in (select distinct language from b_shelf))

											union
											select b_code from b_shelf where language in (
											select language `language_name` from b_shelf where language = :language)

											union
											select b_code from b_shelf where language in (
											select language `language_name` from b_shelf where country = :country)
											');
		$result_bibles = $statement_bibles -> execute(array('language' => $language, 'country' => $country));

		$bible_row = $statement_bibles -> fetch();
		$b_code = $bible_row['b_code'];

		if (!$result_bibles)
		{
			log_msg(__FILE__ . ':' . __LINE__ . ' Bibles PDO query exception. Info = {' . json_encode($statement_bibles -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}' );
			//echo "Whoops. We've got issue with PDO connection... Sorry. Please contact support. ";
			//print_r($links['sofia']['pdo']);

		}
	}

	if(isset($chapter)) $chapter_index = $chapter;
	if(isset($verse)) $verse_index = $verse;

	if(isset($b_code) and isset($book) and is_numeric($book) and stripos($b_code, '_http') === FALSE)
		$book_index = $book;
	elseif(isset($b_code) and isset($book) and !is_numeric($book) and stripos($b_code, '_http') === FALSE)
	{
		$book_short_title = $book;

		$statement_translation = $links['sofia']['pdo'] -> prepare(
				'select table_name from b_shelf where b_code = :b_code'
			);

		$result_translation = $statement_translation -> execute(array('b_code' => $b_code));
		if(!$result_translation)
			log_msg(__FILE__ . ':' . __LINE__ . ' Table name PDO query exception. Info = {' . json_encode($statement_translation -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
		$info_row = $statement_translation -> fetch();
		$table_name = $info_row['table_name'];
		$statement_books = $links['sofia']['pdo'] -> prepare(
				'select distinct book from ' . $table_name
			);
		$result_books = $statement_books -> execute();
		if(!$result_books)
		{
			log_msg(__FILE__ . ':' . __LINE__ . ' Books PDO queryexception. Info = {' . json_encode($statement_books -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
		}
		$books_rows = $statement_books -> fetchAll();
		$book_index = 0;
		foreach ($books_rows as $book_row) 
		{
			$book_index++;
			if ($book_row['book'] == $book)
				break;
		}
	}

?>