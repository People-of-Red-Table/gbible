<html>
	<head>
		<style>
			a:hover, a:link, a:visited, a:hover 
			{
				text-decoration: none;
			}
		</style>
	</head>
	<body>
<h2>This page for Search Engine robots.</h2>
<?php

	require 'log.php';
	require 'config.php';

	if (isset($_REQUEST['b_code']))
		$b_code = $_REQUEST['b_code'];

	if (isset($_REQUEST['b_code']) and !isset($_REQUEST['book']))
		echo '<meta http-equiv="refresh" content="0; url=./?b_code=' . $_REQUEST['b_code'] . '" />';
	elseif (isset($_REQUEST['b_code']) and isset($_REQUEST['book']) and !isset($_REQUEST['chapter']))
		echo '<meta http-equiv="refresh" content="0; url=./?b_code=' . $_REQUEST['b_code'] . '&book=' . $_REQUEST['book'] . '" />';
	elseif (isset($_REQUEST['b_code']) and isset($_REQUEST['book']) and isset($_REQUEST['chapter']))
		echo '<meta http-equiv="refresh" content="0; url=./?b_code=' . $_REQUEST['b_code'] . '&book=' . $_REQUEST['book'] . '&chapter=' . $_REQUEST['chapter'] . '" />';
	else echo '<meta http-equiv="refresh" content="0; url=./">';


	if (!isset($_REQUEST['b_code']))
	{
		$result = mysqli_query($links['sofia']['mysql'], 
					'select b_code, table_name, title, country, language from b_shelf order by country, language, title');
		echo '<table>';
		if (!$result)
			log_msg(__FILE__ . ' ' . __LINE__ . ' ' . mysqli_error($links['sofia']['mysql']));

		while ($shelf_row = mysqli_fetch_assoc($result))
		{
			echo '<tr><td>' . $shelf_row['country'] . '</td><td>' . $shelf_row['language'] . '</td><td><a href="./html_map.php?b_code=' . $shelf_row['b_code'] . '">' . $shelf_row['title'] . '</a></td></tr>';
		}
		echo '</table>';
	}
	elseif( isset($_REQUEST['b_code']) and (stripos($b_code, 'http')))
	{
		$statement_translation = $links['sofia']['pdo'] -> prepare(
				'select bh.table_name, bh.title, bh.description, bh.copyright, 
				bh.license, l.link, country, language, dialect
				from b_shelf bh 
				join licenses l on l.license = bh.license
				where b_code = :b_code'
			);

		$result_translation = $statement_translation -> execute(array('b_code' => $_REQUEST['b_code']));
		if(!$result_translation)
			log_msg(__FILE__ . ' ' . __LINE__ . ' ' . $statement_translation -> errorInfo());
		$info_row = $statement_translation -> fetch();

		echo '
		<table>
			<tr><td>Title</td><td><b>' . $info_row['title'] . '</b></td></tr>
			<tr><td>Description</td><td>' . $info_row['description'] . '</td></tr>
			<tr><td>Copyright</td><td>' . (empty($info_row['copyright'])? 'n/a': $info_row['copyright']) . '</td></tr>
			<tr><td>License</td><td><a href="' . $info_row['link'] . '" target="_blank">' . $info_row['license'] . '</a></td></tr>
			<tr><td>Country</td><td>' . $info_row['country'] . '</td></tr>
			<tr><td>Language</td><td>' . $info_row['language'] . '</td></tr>
			<tr><td>Dialect</td><td>' . $info_row['dialect'] . '</td></tr>
			<tr><td>Code</td><td>' . $_REQUEST['b_code'] . '</td></tr>
		</table>';
		echo '<h1>' . $info_row['title'] . '</h1>';
		$table_name = $info_row['table_name'];
		
		if (!isset($_REQUEST['book']))
		{
			$statement_books = $links['sofia']['pdo'] -> prepare(
								'select distinct book from ' . $table_name
							);
			$result_books = $statement_books -> execute();
			if(!$result_books)
				log_msg(__FILE__ . ' ' . __LINE__ . ' ' . $statement_books -> errorInfo());
			$books_rows = $statement_books -> fetchAll();

			echo '<table>';
			foreach ($books_rows as $book_row) 
			{
				echo '<tr><td><a href="./html_map.php?b_code=' . $_REQUEST['b_code'] . '&book=' . $book_row['book'] . '">' . $book_row['book'] . '</a></td>';

				echo '<td>';

				$statement_chapters = $links['sofia']['pdo'] -> prepare (
									'select distinct chapter from ' . $table_name 
									.' where book = :book'
								);
				$result_chapters = $statement_chapters -> execute(array('book' => $book_row['book']));

				if(!$result_chapters)
					log_msg(__FILE__ . ' ' . __LINE__ . ' ' . $statement_chapters -> errorInfo());

				$chapters_rows = $statement_chapters -> fetchAll();

				foreach ($chapters_rows as $chapter_row) 
				{
					echo '<a href="./html_map.php?b_code=' . $_REQUEST['b_code'] . '&book=' . $book_row['book'] . '&chapter=' . $chapter_row['chapter'] . '">[' . $chapter_row['chapter'] . ']</a> ';
				}

				echo '</td></tr>';
			}
			echo '</table>';
		}
	}
	
	if ( isset($_REQUEST['book']) and isset($_REQUEST['b_code']) )
	{
		$statement_chapters = $links['sofia']['pdo'] -> prepare (
							'select distinct chapter from ' . $table_name 
							.' where book = :book'
						);
		$result_chapters = $statement_chapters -> execute(array('book' => $_REQUEST['book']));

		if(!$result_chapters)
			log_msg(__FILE__ . ' ' . __LINE__ . ' ' . $statement_chapters -> errorInfo());

		$chapters_rows = $statement_chapters -> fetchAll();

		$chapter_links = '<h3>' . $_REQUEST['book'] . '</h3>';
		foreach ($chapters_rows as $chapter_row) 
		{
			$chapter_links .=  '<a href="./html_map.php?b_code=' . $_REQUEST['b_code'] . '&book=' . $_REQUEST['book'] . '&chapter=' . $chapter_row['chapter'] . '">[' . $chapter_row['chapter'] . ']</a> ';
		}
		echo $chapter_links;

		if (isset($_REQUEST['chapter']))
		{
			$statement_verses = $links['sofia']['pdo'] -> prepare (
						'select startVerse, verseText from ' . $table_name .
						' where book = :book and chapter = :chapter'
				);
			$result_verses = $statement_verses -> execute(array('book' => $_REQUEST['book'], 'chapter' => $_REQUEST['chapter']));

			if(!$result_verses)
				log_msg(__FILE__ . ' ' . __LINE__ . ' ' . $statement_verses -> errorInfo());

			$verses_rows = $statement_verses -> fetchAll();

			foreach ($verses_rows as $verse_row) 
			{
				echo '<p><sup>' . $verse_row['startVerse'] . '</sup> ' . $verse_row['verseText'] . '</p>';
			}

			echo $chapter_links;
		}

		echo '<h3>' . $info_row['title'] . '</h3>';
	}

?>
	</body>
</html>