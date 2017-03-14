<h2><?=$text['top_verses'];?></h2>
<?php
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
					 		select fv.verseID, count(fv.verseID), vpl.book, vpl.chapter, vpl.startVerse, vpl.verseText from fav_verses fv 
					 		join ' . $bible_row['table_name'] . ' vpl on vpl.verseID = fv.verseID group by verseID order by count(fv.verseID) desc
					');


			$result_fav_verses = $statement_fav_verses -> execute();

			if ($result_fav_verses)
			{
				$fav_verses_rows = $statement_fav_verses -> fetchAll();
				foreach ($fav_verses_rows as $fav_verse_row) 
				{
					echo '<blockquote>' . $fav_verse_row['verseText'] . '
						<footer>' . $fav_verse_row['book'] . ' ' . $fav_verse_row['chapter'] 
						. ':' . $fav_verse_row['startVerse'] . ' ' . $bible_row['title'] .' <a href="' . $bible_row['link'] . '">' . $bible_row['license'] . '</a></footer>
						</blockquote><br />';
				}
			}
			else
			{
				$message = $text['top_verses_exception'];
				$msg_type = 'danger';
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