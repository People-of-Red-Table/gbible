<h1>Sign In</h1>
<br />
<?php
	if ($_POST['password'] === $_POST['password_repeat'])
	{
		$row = array(
						'nickname' => $_POST['nickname'], 
						'full_name' => $_POST['full_name'],
						'email' => $_POST['email'],
						'password' => md5($_POST['password']),
						'secret_question' => $_POST['secret_question'],
						'secret_answer' => $_POST['secret_answer'],
						'timezone' => $_POST['timezone'],
						'country' => $_POST['user_country'],
						'language' => $_POST['user_language']
						
					);
		$command = $links['sofia']['pdo'] -> prepare('insert into users (nickname, full_name, email, password, secret_question, secret_answer, inserted, timezone, country, language) 
					values (:nickname,:full_name,:email, :password,:secret_question,:secret_answer, now(), :timezone, :country, :language);');
		$links['sofia']['pdo'] -> beginTransaction();
		$result = $command -> execute($row);
		$links['sofia']['pdo'] -> commit();

		if ($result)
		{ 
			$message = "You are registered user now, please sign in. ";
		}
		else $message = "Whoops. We've got issue. Sorry. Please, contact support.";
	}
	else $message = "You typed different passwords.";
?>
	<p><?=$message;?></p>