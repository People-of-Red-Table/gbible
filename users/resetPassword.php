<h1><?=$text['reset_password'];?></h1>
<br />
	
	<div class="form-group">
			<?php

				$statement = $links['sofia']['pdo'] -> prepare('select secret_question from users where email = :email');
				$statement -> execute(array('email' => $_GET['reset_email']));
				if ($row = $statement -> fetch())
				{
					echo '<label for="resetPasswordSecretQuestion">' . $text['secret_question'] . '</label><p id="resetPasswordSecretQuestion">' . $row['secret_question'] . '</p>';
				}
				else
				{
					$msg_type = 'danger';
					$message = $text['mail_not_found'];
				}
				
				if (isset($message) and isset($msg_type))
				{
					echo "<div class='alert alert-$msg_type'>$message</div>";
				}
			?>	
	</div>
<form method="post">
	<div class="form-group">
			<label for="resetPasswordSecretAnswer"><?=$text['secret_answer'];?></label>
			<input type="text" class="form-control" name="secret_answer" id="resetPasswordSecretAnswer">
	</div>
	<input type="hidden" name="reset_email" value="<?=$_GET['reset_email'];?>">
	<input type="hidden" name="menu" value="users_secretAnswerChecking">
	<!-- TO DO: check email by pattern-->
	<input type="submit" class="btn btn-default form-control" name="submit" value="<?=$text['text_reset'];?>" />


</form>
