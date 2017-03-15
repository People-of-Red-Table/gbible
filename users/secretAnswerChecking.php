<h1><?=$text['reset_password'];?></h1>
<?php
	$statement = $links['sofia']['pdo'] -> prepare('select secret_answer from users where email = :email');
	$statement -> execute(array('email' => $_POST['reset_email']));
	if ($row = $statement -> fetch())
	{
		if ($_POST['secret_answer'] === $row['secret_answer'])
		{
			$verification_code = uniqid() . uniqid();
			$statement = $links['sofia']['pdo'] -> prepare('update users set verification_code = :code where email = :email');
			$result = $statement -> execute(array('code' => $verification_code, 'email' => $_POST['reset_email']));
			
			if(!$result)
			{
				echo '<p class="alert alert-danger">' . $text['reset_password_exception_vcode'] . $text['please_contact_support'] . '</p>';
				log_msg(__FILE__ . ':' . __LINE__ . ' Verification code update exception. $_REQUEST = {' . json_encode($_REQUEST) . '}');
			}
			else
			{
					$html_form = '<form method="post">
				<div class="form-group">
						<label for="answerCheckingPassword">' . $text['text_password'] . '</label>
						<input type="password" class="form-control" name="password" maxlength="16" id="answerCheckingPassword">
				</div>
				<div class="form-group">
						<label for="answerCheckingRepeatPassword">' . $text['repeat_password'] . '</label>
						<input type="password" class="form-control" name="password_repeat" maxlength="16" id="answerCheckingRepeatPassword">
				</div>
				
				<input type="hidden" name="verification_code" value="' . $verification_code . '">
				<input type="hidden" name="menu" value="users_passwordResetting">
				<input type="submit" class="btn btn-default form-control" name="submit" value="' . $text['text_reset'] . '" />
			</form>';
				echo $html_form;
			}
		}
		else
		{
			echo '<p class="alert alert-danger">' . $text['incorrect_secret_answer']  . '</p>';
		}
	}
	else
	{
		echo '<p class="alert alert-danger">' . $text['mail_not_found'] . '</p>';
	}	
?>
