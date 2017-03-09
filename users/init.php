<?php
	session_start();

	$auto_save = array('country', 'language', 'b_code', 'book_index', 'chapter_index', 'book', 'chapter');

	foreach ($auto_save as $item)
	{
		if(isset($_REQUEST[$item]))
		{
			$$item = $_REQUEST[$item];
		}
	}

	if(isset($chapter)) $chapter_index = $chapter;
	if(isset($b_code) and isset($book))
	{
		$statement_translation = $links['sofia']['pdo'] -> prepare(
				'select table_name from b_shelf where b_code = :b_code'
			);

		$result_translation = $statement_translation -> execute(array('b_code' => $b_code));
		$info_row = $statement_translation -> fetch();
		$table_name = $info_row['table_name'];
		$statement_books = $links['sofia']['pdo'] -> prepare(
				'select distinct book from ' . $table_name
			);
		$statement_books -> execute();
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