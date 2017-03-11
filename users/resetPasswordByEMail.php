<h1>Reset Password</h1>
<?php
	if (!isset($_REQUEST['verification_code'])
		and isset($_REQUEST['reset_email']))
	{
		$statement_email = $links['sofia']['pdo'] -> prepare('select full_name, email from users where email = :email');
		$result_email = $statement_email -> execute(array('email' => $_REQUEST['reset_email']));
		if (!$result_email)
		{
			$message = "Your e-mail was not found.";
		}
		else
		{
			$user_row = $statement_email -> fetch();
			$verification_code = uniqid() . uniqid();

			$statement_code = $links['sofia']['pdo'] -> prepare('update users set verification_code = :verification_code where email = :email');
			$result_code = $statement_email -> execute(array('email' => $_REQUEST['reset_email'], 'verification_code' => $verification_code));
			if (!$result_code)
			{
				$message = "Whoops, verification code wasn't set. Please, contact support.";
			}
			else
			{
				mail($email_row['email'], 'Golden Bible - Reset Password', 
					'Greetings, ' . $user_row['full_name'] . '.' . PHP_EOL
					. 'For your account on Bible Site http://' . $_SERVER['HTTP_HOST'] 
					. ' was requested resetting of password. ' . PHP_EOL
					. ' If you want to reset your password, please, click this link: '
					. 'http://' . $_SERVER['HTTP_HOST'] . '/?menu=users_resetPasswordByEMail&verification_code='
					. $verification_code .'&reset_email=' . $user_row['email'] . PHP_EOL . PHP_EOL . PHP_EOL
					. "If you didn't request this operation, just ignore this letter." . PHP_EOL . PHP_EOL
					. 'Thank you.'
				);
				$message = 'Letter to your email ' . $user_row['email'] . ' was sent. Please, check your inbox.';
			}
		}
	}
	elseif (isset($_REQUEST['verification_code']) and 
		(
			!isset($_REQUEST['password']))
		or ($_REQUEST['password'] !== $_REQUEST['password_repeat'])
		)
	{
?>

<form method="post">
	<div class="row">
		<div class="col-md-2">
			<p>Password</p>
		</div>
		<div class="col-md-2">
			<input type="password" name="password" maxlength="16">
		</div>
	</div>

	<div class="row">
		<div class="col-md-2">
			<p>Repeat Password</p>
		</div>
		<div class="col-md-2">
			<input type="password" name="password_repeat" maxlength="16">
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<input type="hidden" name="verification_code" value="<?=$verification_code;?>">
			<input type="hidden" name="email" value="<?=$_REQUEST['reset_email'];?>">
			<input type="hidden" name="menu" value="users_resetPasswordByEMail">
			<p align="center"><input type="submit" name="submit" value="Reset" /></p>
		</div>
	</div>
</form>

<?php
	}
	elseif (isset($_REQUEST['verification_code'])
		and isset($_REQUEST['password'])
		and isset($_REQUEST['password_repeat']))
	{
		if ($_REQUEST['password'] === $_REQUEST['password_repeat'])
		{
			$statement_pswd = $links['sofia']['pdo'] -> prepare(
									'update users set password = md5(:password) where email = :email and verification_code = :verification_code'
							);
			$result_pswd = $statement_pswd -> execute(array(

							'password' => $_REQUEST['password'],
							'email' => $_REQUEST['email'],
							'verification_code' => $_REQUEST['verification_code']
													));
			if ($result_pswd)
				$message = "Password was changed. <a href='./?menu=users_signIn'>Sign In</a><meta http-equiv='refresh' content='2;./?menu=users_signIn'>";
			else $message = "Whoops. We've got issue with resetting password. Please, contact support.";
		}
		else $message = "You typed different passwords.";
	}
?>
<p><?php
	if (isset($message)) echo $message;
?></p>