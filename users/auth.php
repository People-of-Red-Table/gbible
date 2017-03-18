<?php

	if(isset($_GET['menu']) && $_GET['menu'] === 'sign_out')
	{
		session_unset(session_id());
	}

	if(isset($_POST['menu']) && $_POST['menu'] === 'users_signingIn')
	{
		$params = array('email' => $_POST['email'], 'password' => $_POST['password']);
		$statement = $links['sofia']['pdo'] -> prepare('select * from users where email = :email and password = md5(:password)');
		$result_user = $statement -> execute($params);

		if(!$result_user)
		{
			$message = $text['sign_in_exception'] . ' ' . $text['please_contact_support'];
			$msg_type = 'danger';
			log_msg(__FILE__ . ':' . __LINE__ . ' Selecting user durig `Sign In` exception. $_REQUEST = {' . json_encode($_REQUEST) . '}');
		}

		$row = $statement -> fetch();
		if ($row['email'] === $_POST['email'])
		{
			$_SESSION['uid'] = $row['id'];
			$menu = 'bible';
			if(isset($_REQUEST['remember_me']) && ($_REQUEST['remember_me'] == true))
				setcookie(session_name(), session_id(), time() + 30 * 24 * 3600);
			else
				setcookie(session_name(), session_id());
		}
		else
		{
			$menu = 'users_signingIn';
			$msg_type = 'danger';
			$message = $text['incorrect_auth_info'];
			$reset_email = $_POST['email'];
		}
	}

	if (!isset($_SESSION['uid']))
	{
		$_SESSION['uid'] = -1;
		//$_SESSION['role_id'] = 6;
		$_SESSION['timezone'] = 'America/New_York';
		$_SESSION['topics_per_page'] = 25;
		$_SESSION['posts_per_page'] = 25;
		$_SESSION['messages_per_page'] = 25;
	}
	else
	{
		$statement_lhit = $pdo -> prepare('update users set last_hit = now(), remote_addr = :remote_addr where id = :uid');
		$result = $statement_lhit -> execute(['remote_addr' => $_SERVER['REMOTE_ADDR'], 'uid' => $_SESSION['uid'] ]);
		if (!$result)
			log_msg(__FILE__ . ':' . __LINE__ . ' Update user\'s `last_hit` durig `Sign In` exception. $_REQUEST = {' . json_encode($_REQUEST) . '}');
	}


	if ($_SESSION['uid'] > 0)
	{
		$statement = $links['sofia']['pdo'] -> prepare('select * from users join countries cnt on users.country = cnt.name where id = :id');
		$result = $statement -> execute(array('id' => $_SESSION['uid']));

		if (!$result)
			log_msg(__FILE__ . ':' . __LINE__ . ' Selecting signed in user exception. $_REQUEST = {' . json_encode($_REQUEST) . '}');
		else
		{
			$row = $statement -> fetch();
			$charity_country = $row['code'];
			$_SESSION['nickname'] = $row['nickname'];
			$_SESSION['full_name'] = $row['full_name'];
			$_SESSION['email'] = $row['email'];
			//$_SESSION['role_id'] = $row['role_id'];
			//$_SESSION['role'] = $row['name'];
			$_SESSION['timezone'] = $row['timezone'];


			$result_language = mysqli_query($links['sofia']['mysql'],'select code from iso_639_languages where language_name = "' . $row['language'] . '" union select code from iso_639_languages where code = "' . $row['language'] . '"');
			$language_row = mysqli_fetch_assoc($result_language);
			$user_language = $language_row['code'];

			$lang_path = './languages/' . strtolower($user_language) . '.php';

			if (file_exists($lang_path)
				and in_array($user_language, $languages))
			{
				require $lang_path;
			}
			else
			{
				if (stripos($user_language, '-') !== FALSE)
				{
					$user_language = explode('-', $user_language)[0];
					$lang_path = './languages/' . strtolower($user_language) . '.php';					
					if (file_exists($lang_path)
					and in_array($user_language, $languages))
					require $lang_path;
				}
			}
			

			$_SESSION['topics_per_page'] = (empty($row['topics_per_page'])) ? 25 : $row['topics_per_page'];
			$_SESSION['posts_per_page'] = (empty($row['posts_per_page'])) ? 25 : $row['posts_per_page'];
			$_SESSION['messages_per_page'] = (empty($row['messages_per_page'])) ? 25 : $row['messages_per_page'];
		}
	}


	if (!isset($menu) and !isset($_REQUEST['menu']))
		$menu = 'bible';

	if (!isset($menu) and isset($_REQUEST['menu']))
		$menu = $_REQUEST['menu'];

?>