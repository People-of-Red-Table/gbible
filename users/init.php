<?php
	setcookie(session_name(), session_id(), time() + 30 * 24 * 3600);

	require 'auth.php';

	$auto_save = [	'book_index', 'chapter_index', 'book', 'chapter',
					 'b_code', 'country', 'language', 
					'b_code1', 'country1', 'language1',
					 'b_code2', 'country2', 'language2',
					 'tt_b_code', 'tt_country', 'tt_language' ];

	if (isset($_REQUEST['action']) 
		and (stripos($_REQUEST['action'], 'parallel_bibles') !==FALSE) )
		foreach (['tt_b_code1', 'tt_country1', 'tt_language1',
					 'tt_b_code2', 'tt_country2', 'tt_language2'] as $item) 
		{		
			$auto_save[] = $item;
		}

	// load variables
	foreach ($auto_save as $item)
	{
		if(isset($_SESSION[$item]))
		{
			$$item = $_SESSION[$item];
		}
	}

	// get variables from request
	foreach ($auto_save as $item)
	{
		if(isset($_REQUEST[$item]))
		{
			$$item = $_REQUEST[$item];
			$_SESSION[$item] = $$item;
		}
	}

	if (isset($_REQUEST['book']) and !isset($_REQUEST['chapter']))
	{
		$chapter = 1;
	}

	$userBible = new UserBible($pdo, $mysql);
	$userBible -> setCountry($country);
	$userBible -> setLanguage($language);

	if (isset($b_code))
		$userBible -> setBCode($b_code);
	else 
	{
		$userBible -> setBCode('=]');
	}

	$b_code = $userBible -> b_code;

	switch ($menu) 
	{
		case 'parallelBibles':
	
			if (!isset($language1))
				$language1 = $language;
			if (!isset($country1))
				$country1 = $country;
			if (!isset($language2))
				$language2 = $language;
			if (!isset($country2))
				$country2 = $country;
		
			$userBibleA = new UserBible($pdo, $mysql);
			$userBibleA -> setCountry($country1);
			$userBibleA -> setLanguage($language1);
			if (isset($b_code1))
				$userBibleA -> setBCode($b_code1);
			else
			{
				$userBibleA -> setBCode('=]');
			}
			$b_code1 = $userBibleA -> b_code;

			$userBibleB = new UserBible($pdo, $mysql);
			$userBibleB -> setCountry($country2);
			$userBibleB -> setLanguage($language2);
			if (isset($b_code2))
				$userBibleB -> setBCode($b_code2);
			else
			{
				$userBibleB -> setBCode('=]');
			}
			$b_code2 = $userBibleB -> b_code;
			break;
		case 'timetable':
			if (isset($_REQUEST['action']) 
					and (stripos($_REQUEST['action'], 'parallel_bibles') !==FALSE) )
			{
				if (!isset($tt_language1))
					$tt_language1 = $language;
				if (!isset($tt_country1))
					$tt_country1 = $country;
				if (!isset($tt_language2))
					$tt_language2 = $language;
				if (!isset($tt_country2))
					$tt_country2 = $country;
			
				$tt_userBibleA = new UserBible($pdo, $mysql);
				$tt_userBibleA -> setCountry($tt_country1);
				$tt_userBibleA -> setLanguage($tt_language1);
				if (isset($tt_b_code1))
					$tt_userBibleA -> setBCode($tt_b_code1);
				else
				{
					$tt_userBibleA -> setBCode('=]');
				}
				$tt_b_code1 = $tt_userBibleA -> b_code;

				$tt_userBibleB = new UserBible($pdo, $mysql);
				$tt_userBibleB -> setCountry($tt_country2);
				$tt_userBibleB -> setLanguage($tt_language2);
				if (isset($tt_b_code2))
					$tt_userBibleB -> setBCode($tt_b_code2);
				else
				{
					$tt_userBibleB -> setBCode('=]');
				}
				$tt_b_code2 = $tt_userBibleB -> b_code;				
			}
			else
			{
				foreach (['b_code' => 'tt_b_code', 'country' => 'tt_country', 'language' => 'tt_language'] as $key => $value) 
				{
					if(!isset($_REQUEST[$value]))
						$$value = $$key;
				}

				$ttBible = new UserBible($pdo, $mysql);
				$ttBible -> setCountry($tt_country);
				$ttBible -> setLanguage($tt_language);

				if (isset($tt_b_code))
					$ttBible -> setBCode($tt_b_code);
				else 
				{
					$ttBible -> setBCode('=]');
				}

				$tt_b_code = $ttBible -> b_code;
			}
			break;
		default:
			# code...
			break;
	}

	/*$check_array = [
						['country', 'language', 'b_code'],
						['country1', 'language1', 'b_code1'],
						['country2', 'language2', 'b_code2'],
					];
	$counter = 1;
	foreach ($check_array as list($country_var, $language_var, $b_code_var)) 
	{
		if ($counter === 2 and $menu !== 'parallelBibles')
			break;

		$statement_languages = $pdo -> prepare('
											select language `language_name` from b_shelf where language = :language
											union
											select language_name from iso_ms_languages where lower(language_code) = :language and language_name in (select distinct language from b_shelf)
											union
											select language_name from iso_ms_languages where language_name = :language and language_name in (select distinct language from b_shelf)
											union
											select language_name from iso_ms_languages where country_name = :country  and language_name in (select distinct language from b_shelf)
											union
											select language_name from iso_ms_languages where country_code = :country and language_name in (select distinct language from b_shelf)
											union
											select language `language_name` from b_shelf where country = :country
											');
		$statement_languages -> execute(['country' => $$country_var, 'language' => $$language_var]);
		$languages_rows = $statement_languages -> fetchAll();
		$language_found = FALSE;
		foreach ($languages_rows as $row) 
		{
			if ($$language_var === $row['language_name'])
			{
				$language_found = true;
				break;
			}
		}
		if (!$language_found)
			$$language_var = $languages_rows[0]['language_name'];

		$statement_bibles = $links['sofia']['pdo'] -> prepare(
											'select b_code, title from b_shelf where language = :language_name');
		$statement_bibles -> execute(['language_name' => $$language_var]);

		$bibles_rows = $statement_bibles -> fetchAll();

		$b_code_found = FALSE;
		foreach ($bibles_rows as $row) 
		{
			if (isset($$b_code_var) and ($$b_code_var === $row['b_code']))
			{
				$b_code_found = true;
				break;
			}
		}
		if (!$b_code_found)
			$$b_code_var = $bibles_rows[0]['b_code'];
		$counter++;
	}*/

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

	/*if (!isset($b_code))
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

	if ($menu === 'parallelBibles')
	{
		if (!isset($language1))
			$language1 = $language;
		if (!isset($country1))
			$country1 = $country;
		if (!isset($language2))
			$language2 = $language;
		if (!isset($country2))
			$country2 = $country;

		if (!isset($b_code1))
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
			$result_bibles = $statement_bibles -> execute(array('language' => $language1, 'country' => $country1));

			$bible_row = $statement_bibles -> fetch();
			$b_code1 = $bible_row['b_code'];

			if (!$result_bibles)
			{
				log_msg(__FILE__ . ':' . __LINE__ . ' Bibles PDO query exception. Info = {' . json_encode($statement_bibles -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}' );
				//echo "Whoops. We've got issue with PDO connection... Sorry. Please contact support. ";
				//print_r($links['sofia']['pdo']);

			}
		}

		if (!isset($b_code2))
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
			$result_bibles = $statement_bibles -> execute(array('language' => $language2, 'country' => $country2));

			$bible_row = $statement_bibles -> fetch();
			$b_code2 = $bible_row['b_code'];

			if (!$result_bibles)
			{
				log_msg(__FILE__ . ':' . __LINE__ . ' Bibles PDO query exception. Info = {' . json_encode($statement_bibles -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}' );
				//echo "Whoops. We've got issue with PDO connection... Sorry. Please contact support. ";
				//print_r($links['sofia']['pdo']);

			}
		}
		log_msg(__FILE__ . ':' . __LINE__ . ' $b_code1 = `' . $b_code1 . '`, $b_code2 = `' . $b_code2 . '`');
	}*/

	if(isset($chapter)) $chapter_index = $chapter;
	if(isset($verse)) $verse_index = $verse;

	if(isset($b_code) and isset($book) and is_numeric($book) and stripos($b_code, '_http') === FALSE)
		$book_index = $book;


	if (!isset($book) and (stripos($b_code, 'http') === FALSE))
	{
		$statement_translation = $links['sofia']['pdo'] -> prepare(
				'select table_name, title, description from b_shelf where b_code = :b_code'
			);

		$result_translation = $statement_translation -> execute(array('b_code' => $b_code));
		if(!$result_translation)
			log_msg(__FILE__ . ':' . __LINE__ . ' Table name PDO query exception. Info = {' . json_encode($statement_translation -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
		$info_row = $statement_translation -> fetch();
		$bible = ['title' => '', 'description' => ''];
		$bible['title'] = $info_row['title'];
		$bible['description'] = $info_row['description'];
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
		
		$book = $books_rows[0]['book'];	
	}

	if(isset($b_code) and isset($book) and !is_numeric($book) and stripos($b_code, '_http') === FALSE)
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