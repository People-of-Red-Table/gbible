<h1><?=$text['sign_in'];?></h1>
<?php
	if($_SESSION['uid'] > 1)
	{
		$message = $text['already_signed_in'];
		slog_msg('Signed up user tried to enter to "Sign In page", user ID = `' . $_SESSION['uid'] . '`. $_REQUEST = {' . json_encode($_REQUEST) . '}');
	}
?>
<form method="post">
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['text_email'];?></p>
		</div>
		<div class="col-md-2">
			<input type="text" name="email">
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['text_password'];?></p>
		</div>
		<div class="col-md-2">
			<input type="password" name="password" maxlength="16">
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-12">
			<input type="hidden" name="menu" value="users_signingIn">
			<!-- TO DO: check email by pattern-->
			<p align="center"><input type="submit" name="submit" value="<?=$text['sign_in'];?>" /></p>
		</div>
	</div>

</form>