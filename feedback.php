<?php
	
	echo '<h1>' . $text['text_feedback'] . '</h1>';

	if (isset($_REQUEST['subject']))
	{
		if (isset($_SESSION['uid']) and ($_SESSION['uid'] > -1))
			$user_id = $_SESSION['uid'];
		else
		$user_id = null;
		
		$query = 'insert into feedback (user_id,email,full_name,subject,message,inserted) values (:user_id,:email,:full_name,:subject,:message,now());';
		$statement_feedback = $links['sofia']['pdo'] -> prepare($query);
		$statement_feedback_result = $statement_feedback -> execute([

				'user_id' => $user_id,
				'email' => $_REQUEST['email'],
				'full_name' => $_REQUEST['full_name'],
				'subject' => $_REQUEST['subject'],
				'message' => $_REQUEST['message']

			]);

		if ($statement_feedback_result)
		{
			$msg_type = 'success';
			$message =  $text['fb_message_sent'];
		}
		else
		{
			$msg_type = 'danger';
			$message = $text['fb_message_was_not_sent'];
		}
	}

	if (isset($msg_type) and isset($message))
	{
		echo '<div class="alert alert-' . $msg_type . '">' . $message . '</div><br />';
	}

?>
<br />
<form method="post">
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['full_name'];?></p>
		</div>
		<div class="col-md-2">
			<input type="text" maxlength="50" name="full_name" <?php 
				if (isset($_SESSION['full_name']))
					echo ' value="' . $_SESSION['full_name'] . '"'
			?>>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['text_email'];?></p>
		</div>
		<div class="col-md-2">
			<input type="text"  maxlength="100" name="email"<?php 
				if (isset($_SESSION['email']))
					echo ' value="' . $_SESSION['email'] . '"'
			?>>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['text_subject'];?></p>
		</div>
		<div class="col-md-2">
			<input type="text" name="subject" maxlength="100">
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['text_message'];?></p>
		</div>
		<div class="col-md-2">
			<textarea cols="30" rows="10" name="message" maxlength="5000"></textarea>
		</div>
	</div>
	<br />

	<div class="row">
		<div class="col-md-12">
			<input type="hidden" name="menu" value="feedback">
			<!-- TO DO: check email by pattern-->
			<input type="submit" name="submit" value="<?=$text['text_send'];?>" />
		</div>
	</div>
</form>