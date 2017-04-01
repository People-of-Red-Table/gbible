<h2><?=$text['my_favorite_verses'];?></h2><br />
<?php

	if (isset($_REQUEST['action']) and (strcmp($_REQUEST['action'], 'delete_fav_verse') === 0))
	{
		$delete_query = 'delete from `fav_verses` where user_id = :uid and id = :id';
		$delete_statement = $pdo -> prepare($delete_query);
		$result = $delete_statement -> execute(['uid' => $_SESSION['uid'], 'id' => $_REQUEST['id']]);

		$messages[] = check_result($result, $delete_statement, $text['fv_deleting_exception'], __FILE__ . ':' . __LINE__ . ' Deleting from favorites exception.');
		if ($result)
			$messages[] = ['type' => 'info', 'message' => $text['fv_deleted']];
		if (!empty($message))
			echo '<p class="alert alert-' . $message['type'] . '">' . $message['message'] . '</p>';

	}

	if (!isset($_REQUEST['page']))
		$page = 1;
	else
		$page =$_REQUEST['page'];

	$page_nav = '<br /><ul class="pagination">';

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
			$page_nav .= '<li><a href="./?menu=users_myFavoriteVerses&page=1">' . $text['first_page'] . '</a></li>';
		else
			$page_nav .= '<li><a><b>' . $text['first_page'] . '</a></b></li>';

		for ($i=1; $i <= $page_count; $i++)
		{
			if ($page != $i)
				$page_nav .= '<li><a href="./?menu=users_myFavoriteVerses&page=' . $i . '">' . $i . '</a></li>';
			else
				$page_nav .= '<li><a><b>' . $i . '</b></a></li>';
		}
		if ($page != $page_count)
			$page_nav .= '<li><a href="./?menu=users_myFavoriteVerses&page=' . $page_count . '">' . $text['last_page'] . '</a></li>';
		else
			$page_nav .= '<li><a><b>' . $text['last_page'] . '</b></a></li>';
		$page_nav .= '</ul><br /><br />';
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
					 		select fv.id, b.book, b.chapter,  case when bt.shorttitle is null then b.book else bt.shorttitle end `book_title`, b.startVerse, b.verseID, b.verseText, fv.inserted from ' . $bible_row['table_name'] . ' b ' .
							'join fav_verses fv on fv.verseID = b.verseID
				 			left join book_titles bt on b.book = bt.book and bt.language_code = "' . $userBible -> language_code . '"
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
						<footer><a class="hidden-print" href="./?menu=bible&b_code=' . $b_code . '&book=' . $fav_verse_row['book'] . '&chapter=' . $fav_verse_row['chapter'] . '&verse=' . $fav_verse_row['startVerse'] . '" target="_blank">' . $fav_verse_row['book_title'] . ' ' . $fav_verse_row['chapter'] 
						. ':' . $fav_verse_row['startVerse'] . '</a> <span class="visible-print">' . $fav_verse_row['book_title'] . ' ' . $fav_verse_row['chapter'] 
						. ':' . $fav_verse_row['startVerse'] . '</span>' . $bible_row['title'] .' <a href="' . $bible_row['link'] . '">' . $bible_row['license'] . '</a></footer>
						</blockquote><p align="right" class="hidden-print"><a href="users_myFavoriteVerses&action=delete_fav_verse&id=' . $fav_verse_row['id'] . '"><button class="btn btn-sm btn-danger">' . $text['text_delete'] . '</button></a></p><br />';
				}
				echo $page_nav;
				echo '<br /><br /><br /><div class="hidden-print"><p>' . $text['use_ctrl_s'] . '</p>';
				echo '<p>' . $text['use_ctrl_p'] . '</p></div>';
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