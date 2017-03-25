<h2><?=$text['my_favorite_verses'];?></h2><br />
<?php

	if (!isset($_REQUEST['page']))
		$page = 1;
	else
		$page =$_REQUEST['page'];

	$page_nav = '<br />';

	$select_count_query = 'select count(verseID) from fav_verses where user_id = :uid';
	$select_count_statement = $pdo -> prepare($select_count_query);

	$select_count_statement -> execute(['uid' => $_SESSION['uid']]);

	$verse_count = $select_count_statement -> fetch()[0];
	$page_count = floor($verse_count / $_SESSION['tf_verses_per_page']);

	if ($verse_count % $_SESSION['tf_verses_per_page'] !== 0)
		$page_count++;

	if ($page_count > 1)
	{
		if ($page != 1)
			$page_nav .= '<a href="./?menu=users_myFavoriteVerses&page=1">[' . $text['first_page'] . ']</a> ';
		else
			$page_nav .= '<b>[' . $text['first_page'] . ']</b> ';

		for ($i=1; $i <= $page_count; $i++)
		{
			if ($page != $i)
				$page_nav .= '<a href="./?menu=users_myFavoriteVerses&page=' . $i . '">[' . $i . ']</a> ';
			else
				$page_nav .= '<b>[' . $i . ']</b> ';
		}
		if ($page != $page_count)
			$page_nav .= '<a href="./?menu=users_myFavoriteVerses&page=' . $page_count . '">[' . $text['last_page'] . ']</a> ';
		else
			$page_nav .= '<b>[' . $text['last_page'] . ']</b>';
		$page_nav .= '<br /><br />';
	}

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
							limit ' . ($page - 1) * $_SESSION['tf_verses_per_page'] . ', ' . $_SESSION['tf_verses_per_page']
					);


			$result_fav_verses = $statement_fav_verses -> execute(array('user_id' => $_SESSION['uid']));

			if ($result_fav_verses)
			{
				$fav_verses_rows = $statement_fav_verses -> fetchAll();
				echo $page_nav;
				foreach ($fav_verses_rows as $fav_verse_row) 
				{
					echo '<blockquote>' . $fav_verse_row['verseText'] . '
						<footer>' . $fav_verse_row['book'] . ' ' . $fav_verse_row['chapter'] 
						. ':' . $fav_verse_row['startVerse'] . ' ' . $bible_row['title'] .' <a href="' . $bible_row['link'] . '">' . $bible_row['license'] . '</a></footer>
						</blockquote><br />';
				}
				echo $page_nav;
				echo '<br /><br /><br /><p>' . $text['use_ctrl_s'] . '</p>';
			}
			else
			{
				$message = $text['my_favorite_verses_exception'] . ' ' . $text['please_contact_support'];
				$msg_type = 'danger';
				log_msg(__FILE__ . ':' . __LINE__ . ' Selecting favorite verses exception. $_REQUEST = {' . json_encode($_REQUEST) . '}');
			}
		}
		else
		{
			$message = $text['MFV_bible_exception'] . $text['please_contact_support'];
			$msg_type = 'danger';
			log_msg(__FILE__ . ':' . __LINE__ . ' Bible choosing in favorite verses exception. $_REQUEST = {' . json_encode($_REQUEST) . '}');
		}
	}
	if (isset($message) and isset($msg_type))
	{
		echo "<div class='alert alert-$msg_type'>$message</div>";
	}
?>