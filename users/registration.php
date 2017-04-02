<h1><?=$text['sign_in'];?></h1>
<br />
<?php

	$messages = [];


	/*if (strcasecmp($_SESSION['captcha_code'], $_REQUEST['user_captcha'] ) !== 0)
		$messages[] = ['type' => 'danger', 'message' => $text['incorrect_captcha']];*/

	if ($_POST['password'] !== $_POST['password_repeat'])
		$messages[] = ['type' => 'danger', 'message' => $text['different_passwords']];


	if (strlen($_POST['password']) < 5)
		$messages[] = ['type' => 'danger', 'message' => $text['pas_length_warning']];

	$email_pattern = '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';

	if (!preg_match($email_pattern, $_REQUEST['email']))
		$messages[] = ['type' => 'danger', 'message' => $text['email_warning']];

	if (empty($messages))
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
			$messages[] = ['type' => 'success', 'message' => $text['registered_sign_in'] . ' <a href="./?menu=users_signIn" class="alert-link">' . $text['sign_in'] . '</a>. '];
			$letter = str_ireplace('%user_name%', $_POST['full_name'], $text['reg_mail']);
			$letter = str_ireplace('%password%', $_POST['password'], $letter);
			@mail($_POST['email'], $text['welcome_subject'], $letter);
		}
		else 
		{
			$messages[] = ['type' => 'danger', 'message' => $text['registration_exception'] . ' ' . $text['please_contact_support']];
			log_msg(__FILE__ . ':' . __LINE__ . ' Inserting new user exception. $_POST = {' . json_encode($_POST) . '}');
		}
	}
	
	$dang = FALSE;
	foreach ($messages as $item) 
	{
		if ($item['type'] === 'danger')
			$dang = true;
		echo '<div class="alert alert-' . $item['type'] . '">' . $item['message'] . '</div>';
	}

	if ($dang)
		echo '<a href="#" onclick="window.history.go(-1)">' . $text['text_return'] . '</a>';
?>