<h1><?=$text['reset_password'];?></h1>
<?php
	//$messages = [];
	if (!isset($_REQUEST['verification_code'])
		and isset($_REQUEST['email']))
	{
		$statement_email = $links['sofia']['pdo'] -> prepare('select full_name, email from users where email = :email');
		$result_email = $statement_email -> execute(array('email' => $_REQUEST['email']));
		if (!$result_email or $statement_email -> rowCount() === 0)
		{
			$msg_type = 'danger';
			$message = $text['mail_not_found'];
		}
		else
		{
			$user_row = $statement_email -> fetch();
			$verification_code = uniqid() . uniqid();

			$statement_code = $links['sofia']['pdo'] -> prepare('update users set verification_code = :verification_code where email = :email');
			$result_code = $statement_code -> execute(array('email' => $_REQUEST['email'], 'verification_code' => $verification_code));
			if (!$result_code)
			{
				$msg_type = 'danger';
				$message = $text['verification_code_exception'] . ' ' . $text['please_contact_support'];
				log_msg(__FILE__ . ':' . __LINE__ . ' Verification code wasn\'t set. Info = {' . json_encode($statement_code -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
			}
			else
			{
				$email_text = $text['reset_password_mail'];

				$email_text = str_ireplace('%user_name%', $user_row['full_name'], $email_text);
				$email_text = str_ireplace('%http_host%', $_SERVER['HTTP_HOST'], $email_text);
				$email_text = str_ireplace('%verification_code%', $verification_code, $email_text);
				$email_text = str_ireplace('%user_email%', $user_row['email'], $email_text);

				$result_mail_send = mail($user_row['email'], $text['golden_bible'] . ' - ' . $text['reset_password'], $email_text);

				if ($result_mail_send)
				{
					$msg_type = 'info';
					$message = str_ireplace('%user_email%', $user_row['email'], $text['reset_mail_sent']);
				}
				else
				{
					$msg_type = 'danger';
					$message = str_ireplace('%user_email%', $user_row['email'], $text['reset_mail_sent_not']) . ' ' . $text['please_contact_support'];
					log_msg(__FILE__ . ':' . __LINE__ . ' Reset password letter wasn\'t sent. $_REQUEST = {' . json_encode($_REQUEST) . '}');
				}
			}
		}
	}
	
	if (isset($_REQUEST['verification_code']) and 
		(
			!isset($_REQUEST['password']))
		or ($_REQUEST['password'] !== $_REQUEST['password_repeat'])
		)
	{
		$statement_check = $pdo -> prepare('select id from `users` where email = :email and verification_code = :verification_code');
		$statement_check -> execute(['email' => $_REQUEST['email'], 'verification_code' => $_REQUEST['verification_code']]);
		if  ($statement_check -> rowCount() === 0)
		{
			$msg_type = 'danger';
			$message = $text['reset_password_email_vf_exception'];
		}
		else
		{
?>

<form method="post">
	<div class="form-group">
			<label for="resetPasswordEMPasswordField"><?=$text['text_password'];?></label>
			<input type="password" class="form-control" name="password" maxlength="16" id="resetPasswordEMPasswordField">
	</div>
	<div class="form-group">
			<label for="resetPasswordEMRepeatPasswordField"><?=$text['repeat_password'];?></label>
			<input type="password" class="form-control" name="password_repeat" maxlength="16" id="resetPasswordEMRepeatPasswordField">
	</div>

	<input type="hidden" name="verification_code" value="<?=$_REQUEST['verification_code'];?>">
	<input type="hidden" name="email" value="<?=$_REQUEST['email'];?>">
	<input type="hidden" name="menu" value="users_resetPasswordByEMail">
	<input type="submit" class="btn btn-default form-control" name="submit" value="<?=$text['text_reset'];?>" />
</form>

<?php
		}
	}
	
	elseif (isset($_REQUEST['verification_code'])
		and isset($_REQUEST['email'])
		and isset($_REQUEST['password'])
		and isset($_REQUEST['password_repeat']))
	{
		if ($_REQUEST['password'] === $_REQUEST['password_repeat'])
		{
			$statement_pswd = $pdo -> prepare(
									'update users set `password` = md5(:password), verification_code = null where (email = :email) and (verification_code = :verification_code)'
							);
			$pdo -> beginTransaction();
			$result_pswd = $statement_pswd -> execute([
							'password' => $_REQUEST['password'],
							'email' => $_REQUEST['email'],
							'verification_code' => $_REQUEST['verification_code']]);
			$pdo -> commit();
			//log_msg(__FILE__ . ':' . __LINE__ . ' Lines affected: ' . $statement_pswd -> rowCount());
			if ($result_pswd and ($statement_pswd -> rowCount() > 0))
			{
				$msg_type = 'success';
				$message =  $text['password_changed'] . ' <a href="./?menu=users_signIn">' . $text['sign_in'] . '</a><meta http-equiv="refresh" content="2; ./?menu=users_signIn">';
			}
			else
			{
				$msg_type = 'danger';
				$message = $text['reset_password_exception'] . '<br />' . $text['reset_by_email_warning'];//. $text['please_contact_support'];
				log_msg(__FILE__ . ':' . __LINE__ . ' Password wasn\'t updated. $_REQUEST = {' . json_encode($_REQUEST) . '}');
			}
		}
		else 
		{
			$msg_type = 'danger';
			$message = $text['different_passwords'];
		}
	}
	
	if (isset($message) and isset($msg_type))
	{
		echo "<div class='alert alert-$msg_type'>$message</div>";
	}
?>