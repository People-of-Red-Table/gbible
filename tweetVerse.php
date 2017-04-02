<?php

	$statement_tweeted = $links['sofia']['pdo'] 
				-> prepare('select verseID from tweeted_verses where verseID = :verseID');
	$result_tweeted = $statement_tweeted -> execute(array('verseID' => $_REQUEST['id']));

	if ($result_tweeted)
	{
		if ($statement_tweeted -> rowCount() > 0)
		{
			// update
			$statement_update = $links['sofia']['pdo'] 
				-> prepare('update tweeted_verses set times_tweeted = times_tweeted + 1 where verseID = :verseID');
			$result_update = $statement_update -> execute(array('verseID' => $_REQUEST['id']));

			if(!$result_update)
			{
				$message = $text['tweet_verse_exception'];
				$msg_type = 'danger';
			}
		}
		else
		{
			// insert
			$statement_insert = $links['sofia']['pdo'] 
				-> prepare('insert into tweeted_verses (verseID, times_tweeted) values (:verseID, 1);');
			$result_insert = $statement_insert -> execute(array('verseID' => $_REQUEST['id']));

			if(!$result_insert)
			{
				$message = $text['tweet_insert_exception'];
				$msg_type = 'danger';
			}
		}
	}
	else
	{
		
		$message = $text['tweeted_verse_exception'];
		$msg_type = 'danger';
	}

	$book_title_statement = $pdo -> prepare('select shorttitle from book_titles bt where book = :book and language_code = :language_code');
	$book_title_statement -> execute(['book' => $_REQUEST['book'], 'language_code' => $userBible -> language_code]);
	if ($book_title_statement -> rowCount() > 0)
	{
		$book_title = $book_title_statement -> fetch()['shorttitle'];
	}
	else
	{
		$book_title = $_REQUEST['book'];
	}


	$url = 'https://twitter.com/intent/tweet?text=' . urlencode($_REQUEST['first_words']) . 
		urlencode(' [#') . $book_title . urlencode(' ') . $_REQUEST['chapter'] . urlencode(':') . $_REQUEST['verseNumber'] . urlencode('] #Bible')
					. '&via=goldenbible_org';
?>
<div class="alert alert-info"><a class='alert-link' href='<?=$url;?>'><?=$text['wait_for_tweeting'];?></a></div>
<meta http-equiv="refresh" content="1; <?=$url;?>">
<?php
	if (isset($message) and isset($msg_type))
	{
		echo "<div class='alert alert-$msg_type'>$message</div>";
	}
?>