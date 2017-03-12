<h2>My Favorite Verses</h2>
<?php
	// it is program code =] for web page of favorite verses...

	if (isset($b_code))
	{
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
					 		select b.book, b.chapter, b.startVerse, b.verseID, b.verseText, fv.inserted from ' . $bible_row['table_name'] . ' b ' .
							'join fav_verses fv on fv.verseID = b.verseID
							where fv.user_id = :user_id
							order by inserted desc
					');


			$result_fav_verses = $statement_fav_verses -> execute(array('user_id' => $_SESSION['uid']));

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
				$message = "Whoops, we've got issue with favorite verses . Please, contact support.";
				$msg_type = 'danger';
				log_msg(__FILE__ . ':' . __LINE__ . ' Selecting favorite verses exception. $_REQUEST = {' . json_encode($_REQUEST) . '}');
			}
		}
		else
		{
			$message = "Whoops, we've got issue with favorite verses [choosing a Bible]. Please, contact support.";
			$msg_type = 'danger';
			log_msg(__FILE__ . ':' . __LINE__ . ' Bible choosing in favorite verses exception. $_REQUEST = {' . json_encode($_REQUEST) . '}');
		}
	}
	if (isset($message) and isset($msg_type))
	{
		echo "<div class='alert alert-$msg_type'>$message</div>";
	}
?>