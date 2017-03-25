<?php
	if (($_SESSION['uid'] > -1) and isset($_REQUEST['id']))
	{

		if (!isset($_REQUEST['page']))
			$page = 1;
		else $page = $_REQUEST['page'];

		$statement_fav_verse = $links['sofia']['pdo'] -> prepare(
									'select verseID from fav_verses where user_id = :user_id
									and  verseID = :verseID'
								);

		$result_fav_verse = $statement_fav_verse -> execute(array('user_id' => $_SESSION['uid'], 'verseID' => $_REQUEST['id']));

		if ($statement_fav_verse -> rowCount() === 0)
		{
			$statement_fav_verse = $links['sofia']['pdo'] -> prepare(
										'insert into fav_verses (user_id, verseID, b_code, inserted)
											values (:user_id, :verseID, :b_code ,now());'
									);

			$result_fav_verse = $statement_fav_verse -> execute(array('user_id' => $_SESSION['uid'], 'verseID' => $_REQUEST['id'], 'b_code' => $_REQUEST['b_code']));

			if ($result_fav_verse)
			{
				$message = $text['verse_added_to_favorites'];
				$msg_type = 'success';
			}
			else
			{
				$message = $text['favorite_verses_exception'] . ' ' . $text['please_contact_support'];
				$msg_type = "danger";
				log_msg(__FILE__ . ':' . __LINE__ . ' Inserting favorite verse exception. $_REQUEST = {' . json_encode($_REQUEST) . '}');
			}
		}
		else
		{
			$message = $text['verse_already_in_fav'];
			$msg_type = 'info';
		}
	}
?>
<div class="alert alert-<?=$msg_type;?>"><?=$message;?></div>