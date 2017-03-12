<h1>Reset Password</h1>
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
				echo '<p class="alert alert-danger">Whoops. We\'ve got issue with password reset. Sorry. Please, contact support.</p>';
				log_msg(__FILE__ . ':' . __LINE__ . ' Verification code update exception. $_REQUEST = {' . json_encode($_REQUEST) . '}');
			}
			else
			{
					$html_form = '<form method="post">
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
						<input type="hidden" name="menu" value="users_passwordResetting">
						<p align="center"><input type="submit" name="submit" value="Reset" /></p>
					</div>
				</div>
			</form>';
				echo $html_form;
			}
		}
		else
		{
			echo '<p class="alert alert-danger">Secret answer is incorrect.</p>';
		}
	}
	else
	{
		echo '<p class="alert alert-danger">Information for your email not found.</p>';
	}	
?>
