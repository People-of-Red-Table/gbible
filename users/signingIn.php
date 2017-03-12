<h1>Sign In</h1>
<?php
	if(isset($message))
		echo "<p class='alert alert-$msg_type'>$message</p>
		<p>
		<a href='./?menu=users_signIn'>Sign In</a><br />
		<a href='./?menu=users_resetPasswordByEMail&reset_email=$reset_email'>Reset password by e-mail</a><br />
		<a href='./?menu=users_resetPassword&reset_email=$reset_email'>Reset password by secret question</a>.
		</p>";
?>