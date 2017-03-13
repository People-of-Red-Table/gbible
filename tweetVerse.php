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


	$url = 'https://twitter.com/intent/tweet?text=' . urlencode($_REQUEST['first_words']) . 
		urlencode(' [') . $_REQUEST['book'] . urlencode(' ') . $_REQUEST['chapter'] . urlencode(':') . $_REQUEST['verseNumber'] . urlencode('] #Bible')
					. '&via=goldenbible_org';
?>
<div class="alert alert-info"><?=$text['wait_for_tweeting'];?></div>
<meta http-equiv="refresh" content="1; <?=$url;?>">
<?php
	if (isset($message) and isset($msg_type))
	{
		echo "<div class='alert alert-$msg_type'>$message</div>";
	}
?>