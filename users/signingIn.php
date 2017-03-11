<h1>Sign In</h1>
<?php
	if(isset($message))
		echo "<p class='alert'>$message</p>
		<p>
			<a href='./?menu=users_resetPassword&reset_email=$reset_email'>Reset password by secret question</a>.<br />
			<a href='./?menu=users_resetPasswordByEMail&reset_email=$reset_email'>Reset password by e-mail</a>.
		</p>";
?>