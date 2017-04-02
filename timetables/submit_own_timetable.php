<?php
	
	if (stripos($tt_b_code, 'http') !== FALSE)
	{
		echo '<p class="alert alert-danger">' . $text['choose_not_h_bibles'] . '</p>';
		break;
	}
	$day_of_week = $_REQUEST['day_of_week'];
	$chapters_in = $_REQUEST['chapters_in'];	

	// create new timetable for this $tt_b_code

	$statement_chapters = $pdo -> prepare('select distinct book, chapter from ' . $info_row['table_name']);
	$result_chapters = $statement_chapters -> execute();
	$chapters_amount = $statement_chapters -> rowCount();

	if (!$result_chapters or ($chapters_amount === 0))
	{
		$messages[] = ['type' => 'danger', 'message' => $text['timetable_exception']];
		log_msg(__FILE__ . ':' . __LINE__ . ' Timetable PDO query exception. Info = ' . json_encode($statement_chapters -> errorInfo()) . ', $_REQUEST = ' . json_encode($_REQUEST) . ', statement = ' . json_encode($statement_chapters));
	}

	$tt_date = new DateTime($_REQUEST['from_date']);
	$interval = new DateInterval('P1D');

	$insert_timetable_query = 'insert into timetables (user_id, title, b_code, scheduled) values (:user_id, :title, :b_code, now());';

	$insert_statement = $pdo -> prepare($insert_timetable_query);
	$result = $insert_statement -> execute(['user_id' => $_SESSION['uid'], 'title' => $_REQUEST['timetable_title'], 'b_code' => $tt_b_code]);

	$message = check_result($result, $insert_statement, $text['scheduling_exception'], '`timetables` insert PDO query exception.');

	if (!empty($message))
		echo '<p class="alert alert-' . $message['type'] . '">' . $message['message'] . '</p>';

	if ($result)
	{
		$select_query = 'select max(id) from timetables where user_id = :user_id and b_code = :b_code';

		$select_statement = $pdo -> prepare($select_query);
		$result = $select_statement -> execute(['user_id' => $_SESSION['uid'], 'b_code' => $tt_b_code]);
		$message = check_result($result, $insert_statement, $text['scheduling_exception'], '`timetables` select PDO query exception.');
		if (!empty($message))
			echo '<p class="alert alert-' . $message['type'] . '">' . $message['message'] . '</p>';
		if ($result)
		{

			$timetable_id = $select_statement -> fetch()['max(id)'];

			$insert_timetable_query = 'insert into schedules (timetable_id, book, chapter, `when`) values ';
			while($chapters_amount > 0)
			{
				$day_of_week_index = strtolower($tt_date -> format('l'));
				$chapters_for_a_day = 0;
				if (isset($day_of_week[$day_of_week_index]))
				{
					$chapters_for_a_day = $chapters_in[$day_of_week_index];

					for($i = 1; $i <= $chapters_for_a_day; $i++)
					{
						$row = $statement_chapters -> fetch();
						if ($row)
						{
							$tt_book = $row['book'];
							$tt_chapter = $row['chapter'];
							$insert_timetable_query .= '(' . $timetable_id . ', "' . $tt_book . '", ' . $tt_chapter . ', "' . $tt_date -> format('Y-m-d') . '")' . PHP_EOL . ',';
						}
					}
				}
				$tt_date -> add($interval);
				$chapters_amount -= $chapters_for_a_day;
			}
			$insert_timetable_query[strlen($insert_timetable_query)-1] = ';';
			$insert_result = mysqli_query($mysql, $insert_timetable_query);

			if (!$insert_result)
			{
				$messages[] = ['type' => 'danger', 'message' => $text['timetable_create_def_tt_exception']];
				log_msg(__FILE__ . ':' . __LINE__ . ' Timetable PDO query exception. Info = "' . mysqli_error($mysql) . '", $_REQUEST = ' . json_encode($_REQUEST) . ', query = ' . $insert_timetable_query);
			}
		}
	}

	
?>