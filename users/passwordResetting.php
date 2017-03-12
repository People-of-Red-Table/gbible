<h1>Reset Password</h1>
<?php
	if($_POST['password'] === $_POST['password_repeat'])
	{
		$result = $statement = $links['sofia']['pdo'] -> prepare('update users set password = md5(:password), verification_code = null where verification_code = :code');
		$statement -> execute(array('code' => $_POST['verification_code'], 'password' => $_POST['password']));

		if(!$result)
		{
			echo "<p class='alert alert-danger'>Whoops. We've got issue with password reset. Sorry. Please, contact support.</p>";
			log_msg(__FILE__ . ':' . __LINE__ . ' Password update exception. $_POST = {' . json_encode($_POST) . '}.');
		}
		else
		{
			echo '<p class="alert alert-danger">Password changed.</p> <p><a href="./?menu=users_signIn">Sign In</a></p>';
		}
	}
	else
	{
		?>
<p class="alert alert-danger">You typed different passwords.</p>


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
			<input type="hidden" name="verification_code" value="<?=$_POST['verification_code'];?>">
			<input type="hidden" name="menu" value="auth_passwordResetting">
			<p align="center"><input type="submit" name="submit" value="Reset" /></p>
		</div>
	</div>
</form>
		<?php
	}
?>