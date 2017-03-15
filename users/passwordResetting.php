<h1>Reset Password</h1>
<?php
	if($_POST['password'] === $_POST['password_repeat'])
	{
		$result = $statement = $links['sofia']['pdo'] -> prepare('update users set password = md5(:password), verification_code = null where verification_code = :code');
		$statement -> execute(array('code' => $_POST['verification_code'], 'password' => $_POST['password']));

		if(!$result)
		{
			echo '<p class="alert alert-danger">' . $text['password_reset_exception'] . ' ' . $text['please_contact_support'] . '</p>';
			log_msg(__FILE__ . ':' . __LINE__ . ' Password update exception. $_REQUEST = {' . json_encode($_REQUEST) . '}.');
		}
		else
		{
			echo '<p class="alert alert-success">' . $text['password_changed'] . '</p> <p><a href="./?menu=users_signIn">' . $text['sign_in'] . '</a></p>';
		}
	}
	else
	{
		?>
<p class="alert alert-danger"><?=$text['different_passwords'];?></p>


<form method="post">
	<div class="form-group">
			<label for="passwordResetPasswordField"><?=$text['text_password'];?></label>
			<input type="password" class="form-control" name="password" maxlength="16" id="passwordResetPasswordField">
	</div>

	<div class=form-group>
			<label for="passwordResetRepeatPasswordField"><?=$text['repeat_password'];?></label>
			<input type="password" class="form-control" name="password_repeat" maxlength="16" id="passwordResetRepeatPasswordField">
	</div>

	<input type="hidden" name="verification_code" value="<?=$_POST['verification_code'];?>">
	<input type="hidden" name="menu" value="auth_passwordResetting">
	<input type="submit" class="btn btn-default form-control" name="submit" value="<?=$text['text_reset'];?>" />
</form>
<?php
}
?>