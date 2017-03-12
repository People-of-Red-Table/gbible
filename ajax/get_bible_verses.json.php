<?php
	require "../config.php";
	if (!isset($_REQUEST['b_code'])) exit;
	if(!isset($_REQUEST['book_index'])) $book_index = 1;
	else $book_index = $_REQUEST['book_index'];
	if(!isset($_REQUEST['chapter'])) $chapter = 1;
	else $chapter = $_REQUEST['chapter'];

	setcookie('chapter_index', $chapter, time() + 30 * 24 * 3600); 

	$statement_table_name = $links['sofia']['pdo'] -> prepare(
							'select table_name from b_shelf where b_code = :b_code');
	$result_table_name = $statement_table_name -> execute(array('b_code' => $_REQUEST['b_code']));

	if (!$result_table_name)
	{
		log_msg(__FILE__ . ':' . __LINE__ . ' Table name PDO exception. ' . json_encode($statement_table_name -> errorInfo()) . ', $_REQUEST = {' . json_encode($_REQUEST) . '}');
		//echo "Whoops. We've got issue with PDO connection... Sorry. Please contact support. "
		;
		//print_r($links['sofia']['pdo']);

	}
	else
	{	
		$table_name_row = $statement_table_name -> fetch();
		$table_name = $table_name_row['table_name'];

		// books sometimes have different shortnames and have only shortnames in those VPL
		$statement_books = $links['sofia']['pdo'] -> prepare(
					'select distinct book from ' . $table_name
														);
		$result_books = $statement_books ->	execute();

		if(!$result_books)
			log_msg(__FILE__ . ':' . __LINE__ . ' Books PDO exception. ' . json_encode($statement_books -> errorInfo()) . ', $_REQUEST = {' . json_encode($_REQUEST) . '}');


		$books_rows = $statement_books -> fetchAll();

		$book_name = $books_rows[$book_index - 1]['book'];
		$statement_verses = $links['sofia']['pdo'] -> prepare('
			select startVerse, verseText from ' . $table_name . ' where book = :book_name and chapter = :chapter'
														);
		$result_verses = $statement_verses -> execute(array(
				'book_name' => $book_name,
				'chapter' => $chapter
			));

		if(!$result_verses)
			log_msg(__FILE__ . ':' . __LINE__ . ' Verses PDO exception. ' . $statement_verses -> errorInfo() . ', $_REQUEST = {' . json_encode($_REQUEST) . '}');

		echo json_encode($statement_verses -> fetchAll());
	}
?>