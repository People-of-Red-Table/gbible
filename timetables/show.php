	<form method="post">
		<input type="hidden" name="menu" value="timetable">
		<div class="form-group">
			<label for="timetableDateField"><?=$text['text_date'];?></label>
			<input type="date" name="date" class="form-control" value="<?=$date->format('Y-m-d');?>" />
		</div>
		<input type="submit" class="btn btn-default form-control" name="submit" value="<?=$text['text_open'];?>">
	</form>
	<?php

		$timetables_statement = $pdo -> prepare('select id, title, b_code, b_code2 from timetables where user_id = :user_id');
		$result = $timetables_statement -> execute(['user_id' => $_SESSION['uid']]);
		$message = check_result($result, $timetables_statement, $text['tt_schedules_exception'], 'Timetables select PDO query exception.');
		if (!empty($message))
			echo '<p class="alert alert-' . $message['type'] . '">' . $message['message'] . '</p>';

		if ($result)
		{
			$timetables_rows = $timetables_statement -> fetchAll();
			foreach ($timetables_rows as $timetable_row) 
			{
				echo '<br/><h3>' . $timetable_row['title'] . '</h3><br/>';
				$select_query = 'select s.book, case when bt.shorttitle is null then s.book else bt.shorttitle end `title`, s.chapter, s.`read` from schedules s
								left join book_titles bt on s.book = bt.book and bt.language_code = :language_code
								where s.timetable_id = :timetable_id
								and s.`when` = :date';
				$select_readings_statement = $pdo -> prepare($select_query);
				$result = $select_readings_statement -> execute(['timetable_id' => $timetable_row['id'], 'date' => $date -> format('Y-m-d'), 'language_code' => $interface_language]);
				$message = check_result($result, $select_readings_statement, $text['tt_readings_exception'], 'Select readings for today exception.');
				if (!empty($message))
					echo '<p class="alert alert-' . $message['type'] . '">' . $message['message'] . '</p>';
				
				if ($result)
				{
					if ($select_readings_statement -> rowCount() === 0)
						echo '<p class="alert alert-info">' . $text['schedule_have_no_reading_today'] . '</p>';
					else
					while($reading_row = $select_readings_statement -> fetch())
					{
						$alert = '';
						if (!is_null($reading_row['read']))
							$alert = ' class="alert alert-success"';
						else
							$alert = ' class="alert alert-warning"';

						if (is_null($timetable_row['b_code2']))
							echo '<a href="./?menu=bible&b_code=' . $timetable_row['b_code'] . '&book=' . $reading_row['book'] . '&chapter=' . $reading_row['chapter'] . '" target="_blank"><span' . $alert . '><b>' . $reading_row['title'] .'</b> ' . $reading_row['chapter'] . '</span></a> ';
						else
							echo '<a href="./?menu=parallelBibles&b_code1=' . $timetable_row['b_code'] . '&b_code2=' . $timetable_row['b_code2'] . '&book=' . $reading_row['book'] . '&chapter=' . $reading_row['chapter'] . '" target="_blank"><span' . $alert . '><b>' . $reading_row['title'] .'</b> ' . $reading_row['chapter'] . '</span></a> ';
					}
					echo '<br/>';
				}
				echo '<br /><form method="post">
						<input type="hidden" name="menu" value="timetable">
						<input type="hidden" name="action" value="unschedule_timetable_of_user">
						<input type="hidden" name="id" value="' . $timetable_row['id'] .'">
						<input type="submit" class="btn btn-danger form-control" name="submit" value="' . $text['to_unschedule'] . '"></form>';
			}
		}
		if($date -> format('md') != '0229')
		{
			$statement_schedules = $pdo -> prepare('select user_id, b_code, scheduled from bible_for_a_year_schedules where user_id = :user_id and scheduled is not null');
			$statement_schedules -> execute(['user_id' => $_SESSION['uid']]);
			$schedules_rows = $statement_schedules -> fetchAll();

			$bfy_scheduled = FALSE;
			foreach ($schedules_rows as $row)
			{
				$messages[] = display_bfy_schedule($row['b_code'], $date);
				if (strcasecmp($tt_b_code, $row['b_code']) === 0)
					$bfy_scheduled = true;

				echo '<br /><br /><form method="post">
						<input type="hidden" name="menu" value="timetable">
						<input type="hidden" name="action" value="unschedule">
						<input type="hidden" name="bfy_b_code" value="' . $row['b_code'] .'">
						<input type="submit" class="btn btn-danger form-control" name="submit" value="' . $text['to_unschedule'] . '"></form>';
			}

			if(stripos($tt_b_code, 'http') === FALSE)
			{
				if (!$bfy_scheduled)
				{
					$messages[] = display_bfy_schedule($tt_b_code, $date);
					echo '<br /><br /><form method="post">
							<input type="hidden" name="menu" value="timetable">
							<input type="hidden" name="action" value="schedule">
							<input type="submit" class="btn btn-default form-control" name="submit" value="' . $text['to_schedule'] . '"></form>';
				}
			}
			else
			{
				$messages[] = ['type' => 'info', 'message' => $text['choose_not_h_bibles']];
			}

		}
		else
		{
			$messages[] = ['type' => 'info', 'message' => $text['timetable_229']];
		}

		echo '<br />';
		foreach ($messages as $item) 
		{
			if (isset($item['type']))
				echo '<p class="alert alert-' . $item['type'] . '">' . $item['message'] . '</p>';
		}
	?>
	<form method="post">
		<input type="hidden" name="menu" value="timetable" />
		<input type="hidden" name="action" value="create_own_timetable" />
		<input type="submit" class="btn btn-default form-control" title="<?=$text['create_timetable_note'];?>" name="submit" value="<?=$text['create_own_timetable'];?>">
	</form>
	<br/><form method="post">
		<input type="hidden" name="menu" value="timetable" />
		<input type="hidden" name="action" value="show" />
		<input type="submit" class="btn btn-default form-control" name="submit" value="<?=$text['title_refresh'];?>">
	</form>