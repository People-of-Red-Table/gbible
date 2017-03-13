<h1>Reset Password</h1>
<br />
<form method="post">
	
	<div class="row">
		<div class="col-md-12">
			<?php

				$statement = $links['sofia']['pdo'] -> prepare('select secret_question from users where email = :email');
				$statement -> execute(array('email' => $_GET['reset_email']));
				if ($row = $statement -> fetch())
				{
					echo '<p>Secret Question: ' . $row['secret_question'] '</p>';
				}
				else
				{
					$msg_type = 'danger';
					$message = 'Information for your email was not found.';
				}
				
				if (isset($message) and isset($msg_type))
				{
					echo "<div class='alert alert-$msg_type'>$message</div>";
				}
			?>	
		</div>
	</div>

	<div class="row">
		<div class="col-md-2">
			<p>Secret Answer</p>
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
			<p align="center"><input type="submit" name="submit" value="Reset" /></p>
		</div>
	</div>


</form>
