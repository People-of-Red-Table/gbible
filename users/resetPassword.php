<h1><?=$text['reset_password'];?></h1>
<br />
<form method="post">
	
	<div class="row">
		<div class="col-md-12">
			<?php

				$statement = $links['sofia']['pdo'] -> prepare('select secret_question from users where email = :email');
				$statement -> execute(array('email' => $_GET['reset_email']));
				if ($row = $statement -> fetch())
				{
					echo '<p>' . $text['secret_question'] . ': ' . $row['secret_question'] '</p>';
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
	</div>
	<br />
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['secret_answer'];?></p>
		</div>
		<div class="col-md-2">
			<input type="text" name="secret_answer">
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<input type="hidden" name="reset_email" value="<?=$_GET['reset_email'];?>">
			<input type="hidden" name="menu" value="users_secretAnswerChecking">
			<!-- TO DO: check email by pattern-->
			<p align="center"><input type="submit" name="submit" value="<?=$text['text_reset'];?>" /></p>
		</div>
	</div>


</form>
