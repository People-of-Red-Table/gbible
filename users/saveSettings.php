<h1><?=$text['text_settings'];?></h1>
<br />
<?php

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
	$statement_update_settings = $links['sofia']['pdo'] -> prepare('update users set nickname = :nickname, full_name = :full_name, email = :email, secret_question = :secret_question, secret_answer = :secret_answer, updated = now(), timezone = :timezone, country = :country, language = :language,
		updated_by = :updated_by where id = :id;');
	try
	{
		$result = $statement_update_settings -> execute($row);
	}
	catch (PDOException $e)
	{
		echo $e -> getMessage();
	}

	$res = mysqli_query($links['sofia']['mysql'], 'select password from users where id = ' . $_SESSION['uid']);
	$row = mysqli_fetch_assoc($res);
	$messages = [];

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
					$messages[] = ['type' => 'success', 'text' => 'Password saved. '];
				}
				else 
				{
					$messages[] = ['type' => 'danger', 'text' => $text['password_saving_exception'] . $text['please_contact_support']];
					log_msg(__FILE__ . ':' . __LINE__ . ' Password update in settings. Exception. Info = {' . json_encode($statement -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
				}
			}
			else
			{ 
				$messages[] = ['type' => 'danger', 'text' => $text['different_passwords']];
			}
		}
		else
		{
			$messages[] = ['type' => 'danger', 'text' => $text['settings_incorrect_password']];
		}
	}
	if ($result)
	{ 
		$messages[] = ['type' => 'success', 'text' => $text['settings_saved'] . '<meta http-equiv="refresh" content="2; ./?menu=bible">'];
	}
	else 
	{
			$messages[] = ['type' => 'danger', 'text' => $text['saving_settings_exception'] . $text['please_contact_support']];
			log_msg(__FILE__ . ':' . __LINE__ . ' Settings saving. Exception. Info = {' . json_encode($statement_update_settings -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
	}

	if ((count($messages) == 1) and ($messages[0]['type'] === 'success'))
		$messages[] = ['type' => 'success', 'text' => $text['text_hallelujah']];	

	foreach ($messages as $item) 
	{
		echo '<div class="alert alert-' . $item['type'] . '">' . $item['text'] . '</div>';
	}
?>