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
<div class="alert alert-info"><?=$text['fb_pre_message'];?></div>
<br />
<form method="post">
	<div class="form-group">
		<label for="fb_fullname">
			<p><?=$text['full_name'];?></p>
		</label>
			<input type="text" class="form-control" maxlength="50" name="full_name" id="fb_fullname" <?php 
				if (isset($_SESSION['full_name']))
					echo ' value="' . $_SESSION['full_name'] . '"'
			?>>
	</div>
	<br />
	<div class="form-group">
		<label for="fbEMail">
			<p><?=$text['text_email'];?></p>
		</label>
			<input type="text" class="form-control" maxlength="100" id="fbEMail" name="email"<?php 
				if (isset($_SESSION['email']))
					echo ' value="' . $_SESSION['email'] . '"'
			?>>
	</div>
	<br />
	<div class="form-group">
		<label for="fbSubject">
			<p><?=$text['text_subject'];?></p>
		</label>
			<input type="text" class="form-control" id="fbSubject" name="subject" maxlength="100">
	</div>
	<br />
	<div class="form-group">
		<label for="fbMessage">
			<p><?=$text['text_message'];?></p>
		</label>
			<textarea  class="form-control" cols="30" rows="10" id="fbMessage" name="message" maxlength="5000"></textarea>
	</div>
	<br />

	<input type="hidden" name="menu" value="feedback">
	<!-- TO DO: check email by pattern-->
	<input type="submit" class="btn btn-default form-control" name="submit" value="<?=$text['text_send'];?>" />
</form>