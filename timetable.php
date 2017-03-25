<h1><a href="./?menu=timetable"><?=$text['text_timetable'];?></a></h1><br />
<?php
	if (!isset($_REQUEST['date']))
	{
		$date = new DateTime();
		$date = set_user_timezone($date);
	}
	else $date = new DateTime($_REQUEST['date']);

	$week = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
	$messages = [];

	if(!isset($_REQUEST['action']))
		$action = 'show';
	else
		$action = $_REQUEST['action'];

	$statement_info = $pdo -> prepare('select b_code, title, table_name from b_shelf where b_code = :b_code');
	$statement_info -> execute(['b_code' => $tt_b_code]);

	$info_row = $statement_info -> fetch();

	if (stripos($action, 'parallel_bibles') === FALSE)
		require './timetables/nav.php';
	else
		require './timetables/nav_parallel_bibles.php';	

	$actions = ['schedule', 'unschedule', 'create_own_timetable', 'submit_own_timetable', 'unschedule_timetable_of_user',
				'schedule_parallel_bibles', 'create_schedule_for_parallel_bibles'];

	if (in_array($action, $actions))
		require './timetables/' . $action . '.php';
	else
		require './timetables/show.php';

	if (stripos($action, 'schedule') !== FALSE) 
		require './timetables/show.php';

?>