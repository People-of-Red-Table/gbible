<h1><?=$text['sign_in'];?></h1>
<?php
	if(isset($message))
	{
		$reset_email = $_REQUEST['reset_email'];
		echo '<p class=\'alert alert-' . $msg_type . '\'>' . $message . '</p>
		<p>
		<a href=\'./?menu=users_signIn\'>' . $text['sign_in'] . '</a><br />
		<a href=\'./?menu=users_resetPasswordByEMail&reset_email=' . $reset_email . '\'>' . . '</a>.<br />
		<a href=\'./?menu=users_resetPassword&reset_email=' . $reset_email . '\'>' . $text['reset_by_question'] . '</a>.
		</p>';
	}
?>