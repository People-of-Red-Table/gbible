<h2><?=$text['top_verses'];?></h2><br />
<?php

	if (!isset($_REQUEST['page']))
		$page = 1;
	else
		$page =$_REQUEST['page'];

	$page_nav = '<br /><ul class="pagination">';

	$select_count_query = 'select count(verseID) from fav_verses';
	$select_count_statement = $pdo -> prepare($select_count_query);

	$select_count_statement -> execute();

	$verse_count = $select_count_statement -> fetch()[0];
	$page_count = floor($verse_count / $_SESSION['tf_verses_per_page']);

	if ($verse_count % $_SESSION['tf_verses_per_page'] !== 0)
		$page_count++;

	if ($page_count > 1)
	{
		if ($page != 1)
			$page_nav .= '<li><a href="./?menu=topVerses&page=1">' . $text['first_page'] . '</a></li>';
		else
			$page_nav .= '<li><a><b>' . $text['first_page'] . '</b></a></li>';

		for ($i=1; $i <= $page_count; $i++)
		{
			if ($page != $i)
				$page_nav .= '<li><a href="./?menu=topVerses&page=' . $i . '">' . $i . '</a></li>';
			else
				$page_nav .= '<li><a><b>' . $i . '</b></a></li>';
		}
		if ($page != $page_count)
			$page_nav .= '<li><a href="./?menu=topVerses&page=' . $page_count . '">' . $text['last_page'] . '</a></li>';
		else
			$page_nav .= '<li><a><b>' . $text['last_page'] . '</b></a></li>';
		$page_nav .= '</ul><br /><br />';
	}

	// it is program code =] for web page of top verses...

	if (isset($b_code))
	{
		$b_code_changed = FALSE;
		$changed_b_code = $b_code;
		if (stripos($b_code, 'http') !== FALSE)
		{

			$b_code = 'eng-asv';
			$b_code_changed = true;
		}

		$statement_bible = $links['sofia']['pdo']
		 -> prepare('select sh.b_code, sh.title, sh.license, l.link, sh.table_name from b_shelf sh
		 	join licenses l on sh.license = l.license
		  where b_code = :b_code');
		$result_bible = $statement_bible -> execute(array('b_code' => $b_code));
		$bible_row = $statement_bible -> fetch();

		if ($result_bible)
		{
			$statement_fav_verses = $links['sofia']['pdo']
			 -> prepare('
				 		select fv.verseID, count(fv.verseID), vpl.book, case when bt.shorttitle is null then vpl.book else bt.shorttitle end `book_title`, vpl.chapter, vpl.startVerse, vpl.verseText from fav_verses fv 
				 		join ' . $bible_row['table_name'] . ' vpl on vpl.verseID = fv.verseID
				 		left join book_titles bt on vpl.book = bt.book and bt.language_code = "' . $userBible -> language_code . '"
				 		group by verseID 
				 		order by count(fv.verseID) desc
				 		limit ' . ($page - 1) * $_SESSION['tf_verses_per_page'] . ', ' . $_SESSION['tf_verses_per_page']
					);


			$result_fav_verses = $statement_fav_verses -> execute();

			if ($result_fav_verses)
			{
				$fav_verses_rows = $statement_fav_verses -> fetchAll();
				echo $page_nav;
				foreach ($fav_verses_rows as $fav_verse_row) 
				{
					echo '<blockquote>' . $fav_verse_row['verseText'] . '
						<footer><a class="hidden-print" href="./?menu=bible&b_code=' . $b_code . '&book=' . $fav_verse_row['book'] . '&chapter=' . $fav_verse_row['chapter'] . '&verse=' . $fav_verse_row['startVerse'] . '" target="_blank">' . $fav_verse_row['book_title'] . ' ' . $fav_verse_row['chapter'] 
						. ':' . $fav_verse_row['startVerse'] . '</a> <span class="visible-print">' . $fav_verse_row['book_title'] . ' ' . $fav_verse_row['chapter'] 
						. ':' . $fav_verse_row['startVerse'] . '</span>' . $bible_row['title'] .' <a href="' . $bible_row['link'] . '">' . $bible_row['license'] . '</a></footer>
						</blockquote><br />';
				}
				echo $page_nav;
				echo '<br /><br /><br /><div class="hidden-print"><p>' . $text['use_ctrl_s'] . '</p>';
				echo '<p>' . $text['use_ctrl_p'] . '</p></div>';
			}
			else
			{
				$message = $text['top_verses_exception'];
				$msg_type = 'danger';
				log_msg(__FILE__ . ':' . __LINE__ . ' Select fav verses. Exception. Info = {' . json_encode($statement_fav_verses -> errorInfo()) . '}, REQUEST = {' . json_encode($_REQUEST) . '}');
			}
		}
		else
		{
			$message = $text['top_verses_exception_bible'];
			$msg_type = 'danger';
		}

		if ($b_code_changed)
		{
			$b_code = $changed_b_code;
			$b_code_changed = FALSE;
		}
	}
	if (isset($message) and isset($msg_type))
	{
		echo "<div class='alert alert-$msg_type'>$message</div>";
	}
?>