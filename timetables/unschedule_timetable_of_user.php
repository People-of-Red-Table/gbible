<?php
	if (isset($_REQUEST['id']))
	{
		$timetable_id = $_REQUEST['id'];

		$select_query = 'select id, user_id from timetables where id = :timetable_id and user_id = :uid';
		$select_statement = $pdo -> prepare($select_query);
		$result = $select_statement -> execute(['timetable_id' => $timetable_id, 'uid' => $_SESSION['uid']]);

		$timetable_row = $select_statement -> fetch();

		if (!empty($message))
		{
			echo '<div class="alert alert-' . $message['type'] . '">' . $message['message'] . '</div>';
			$message = [];
		}

		if ($result and ($timetable_row['user_id'] == $_SESSION['uid']))
		{
			$delete_schedules_query =  'delete from `schedules`
										where timetable_id = :timetable_id';
			$delete_schedules_statement = $pdo -> prepare($delete_schedules_query);
			$result = $delete_schedules_statement -> execute(['timetable_id' => $timetable_id]);
			$message = check_result($result, $delete_schedules_statement, $text['unscheduling_exception'], __FILE__ . ':' . __LINE__ . ' Deleting schedules exception.');

			if (!empty($message))
			{
				echo '<div class="alert alert-' . $message['type'] . '">' . $message['message'] . '</div>';
				$message = [];
			}

			$delete_timetable_query = 'delete from timetables where user_id = :uid and id = :id';
			$delete_timetable_statement = $pdo -> prepare($delete_timetable_query);
			$result = $delete_timetable_statement -> execute(['id' => $timetable_id, 'uid' => $_SESSION['uid']]);
			$message = check_result($result, $delete_schedules_statement, $text['unscheduling_exception'], __FILE__ . ':' . __LINE__ . ' Deleting timetable exception.');

			if (!empty($message))
			{
				echo '<div class="alert alert-' . $message['type'] . '">' . $message['message'] . '</div>';
				$message = [];
			}
		}
	}
?>