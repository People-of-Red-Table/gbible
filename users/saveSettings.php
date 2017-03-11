<h1>Settings</h1>
<br />
<?php
	log_msg('inside of saveSettings.php');
	if(isset($_POST['user_country']))
		log_msg(__FILE__ . ':' . __LINE__ . ' user_country = ' . $_POST['user_country']);
	if(isset($_POST['user_language']))
		log_msg(__FILE__ . ':' . __LINE__ . ' user_language = ' . $_POST['user_language']);

	$row = array(
					'nickname' => $_POST['nickname'], 
					'full_name' => $_POST['full_name'],
					'email' => $_POST['email'],
					'secret_question' => $_POST['secret_question'],
					'secret_answer' => $_POST['secret_answer'],
					'timezone' => $_POST['timezone'],
					'updated_by' => $_SESSION['uid'],
					'country' => $_POST['user_country'],
					'language' => $_POST['user_language'],
					'id' => $_SESSION['uid']
				);
	$command = $links['sofia']['pdo'] -> prepare('update users set nickname = :nickname, full_name = :full_name, email = :email, secret_question = :secret_question, secret_answer = :secret_answer, updated = now(), timezone = :timezone, country = :country, language = :language,
		updated_by = :updated_by where id = :id;');
	try
	{
		$result = $command -> execute($row);
	}
	catch (PDOException $e)
	{
		echo $e -> getMessage();
	}

	$res = mysqli_query($links['sofia']['mysql'], 'select password from users where id = ' . $_SESSION['uid']);
	$row = mysqli_fetch_assoc($res);
	$message = '';

	if (!empty($_POST['current_password']))
	{
		if (md5($_POST['current_password']) === $row['password'])
		{
			if($_POST['password'] === $_POST['password_repeat'])
			{
				$statement = $links['sofia']['pdo'] -> prepare('update users set password = md5(:password) where id = :id');
				$res = $statement -> execute(array('id' => $_SESSION['uid'], 'password' => $_POST['password']));
				if ($res)
				{ 
					$message = "Password saved. ";
				}
				else 
				{
					$message = "Whoops. We've got issue with password saving in settings. Sorry. Please, contact support. ";
				}
			}
			else $message .= 'In fields "Password" and "Password Repeat" you typed different passwords. ';
		}
		else
		{
			$message .= 'You typed not your current password. ';
		}
	}
	if ($result)
	{ 
		$message .= "Settings saved. Hallelujah! " . '<meta http-equiv="refresh" content="2; ./?menu=bible">';
	}
	else 
	{
		$message .= "Whoops. We've got issue with settings. Sorry. Please, contact support. ";
	}
?>
<p><?=$message;?></p>