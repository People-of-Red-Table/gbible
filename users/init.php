<?php
	session_start();

	$auto_save = array('country', 'language', 'b_code', 'book_index', 'chapter_index', 'verse_index', 'verse', 'book', 'chapter');

	foreach ($auto_save as $item)
	{
		if(isset($_REQUEST[$item]))
		{
			$$item = $_REQUEST[$item];
		}
	}

	if(isset($chapter)) $chapter_index = $chapter;
	if(isset($verse)) $verse_index = $verse;

	if(isset($b_code) and isset($book) and is_numeric($book))
		$book_index = $book;
	elseif(isset($b_code) and isset($book) and !is_numeric($book))
	{
		$book_short_title = $book;

		$statement_translation = $links['sofia']['pdo'] -> prepare(
				'select table_name from b_shelf where b_code = :b_code'
			);

		$result_translation = $statement_translation -> execute(array('b_code' => $b_code));
		if(!$result_translation)
			log_msg(__FILE__ . ' ' . __LINE__ . ' ' . $statement_translation -> errorInfo());
		$info_row = $statement_translation -> fetch();
		$table_name = $info_row['table_name'];
		$statement_books = $links['sofia']['pdo'] -> prepare(
				'select distinct book from ' . $table_name
			);
		$result_books = $statement_books -> execute();
		if(!$result_books)
			log_msg(__FILE__ . ' ' . __LINE__ . ' ' . $statement_books -> errorInfo());
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