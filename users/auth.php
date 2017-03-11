<?php

	if(isset($_GET['menu']) && $_GET['menu'] === 'sign_out')
	{
		session_unset(session_id());
	}

	if(isset($_POST['menu']) && $_POST['menu'] === 'users_signingIn')
	{
		$params = array('email' => $_POST['email'], 'password' => $_POST['password']);
		$statement = $links['sofia']['pdo'] -> prepare('select * from users where email = :email and password = md5(:password)');
		$statement -> execute($params);
		$row = $statement -> fetch();
		if ($row['email'] === $_POST['email'])
		{
			$_SESSION['uid'] = $row['id'];
			$menu = 'bible';
		}
		else
		{
			$menu = 'users_signingIn';
			$message = 'Authentification information is incorrect.';
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
		$result = mysqli_query($links['sofia']['mysql'], 'update users set last_hit = now(), remote_addr = "' . $_SERVER['REMOTE_ADDR'] . '" where id = ' . $_SESSION['uid']);
	}


	if ($_SESSION['uid'] > 0)
	{
		$statement = $links['sofia']['pdo'] -> prepare('select * from users');
		$statement -> execute(array('id' => $_SESSION['uid']));
		$row = $statement -> fetch();
		$_SESSION['nickname'] = $row['nickname'];
		$_SESSION['email'] = $row['email'];
		//$_SESSION['role_id'] = $row['role_id'];
		//$_SESSION['role'] = $row['name'];
		$_SESSION['timezone'] = $row['timezone'];

		$_SESSION['topics_per_page'] = (empty($row['topics_per_page'])) ? 25 : $row['topics_per_page'];
		$_SESSION['posts_per_page'] = (empty($row['posts_per_page'])) ? 25 : $row['posts_per_page'];
		$_SESSION['messages_per_page'] = (empty($row['messages_per_page'])) ? 25 : $row['messages_per_page'];
	}


	if (!isset($menu) and !isset($_REQUEST['menu']))
		$menu = 'bible';

	if (!isset($menu) and isset($_REQUEST['menu']))
		$menu = $_REQUEST['menu'];

?>