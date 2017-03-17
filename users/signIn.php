<h1><?=$text['sign_in'];?></h1>
<?php
	if($_SESSION['uid'] > 1)
	{
		$message = $text['already_signed_in'];
		slog_msg('Signed up user tried to enter to "Sign In page", user ID = `' . $_SESSION['uid'] . '`. $_REQUEST = {' . json_encode($_REQUEST) . '}');
	}
?>
<br />
<form method="post" name="signInForm">
	<input type="hidden" name="formIsCorrect" id="signInFormIsCorrect" value="false" />
	<div class="form-group" id="emailFormGroup">
			<label for="signInEmailField"><?=$text['text_email'];?></label>
			<input type="email" class="form-control" name="email" id="signInEmailField" value="" onchange="test_email(this.id, 'emailFormGroup', 'signInForm')">
	</div>
	<br />
	<div class="form-group">
			<label for="signInPassword"><?=$text['text_password'];?></label>
			<input type="password" class="form-control" name="password" maxlength="16" id="signInPassword">
	</div>
	<br />
	
	<div class="checkbox">
		<label>
			<input type="checkbox" name="remember_me" /><?=$text['remember_me'];?>
		</label>
	</div>
	<input type="hidden" name="menu" value="users_signingIn">
	<!-- TO DO: check email by pattern-->
	<input type="submit" class="btn btn-default form-control" name="submit" value="<?=$text['sign_in'];?>" />

</form>