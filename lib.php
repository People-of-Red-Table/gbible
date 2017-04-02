<?php

	require './lib/html_verse.php';
	require './lib/get_new_id.php';
	/*
		Tables of "Bible for a Year" readings are having
		two types of readings: [1] Bible for a Year and [2] Daily Readings.
		[1] Just readings from Genesis to Revelation from first to last day of a year;
				year is null here; [Deprecated]
		[2] Readings which are timed to big Christian Holidays: Christmas, Good Friday and Easter.
				year is not null here, cause Easter's date is differs.
		TODO: Daily Readings.
	*/

	function display_bfy_schedule($bfy_b_code, $date)
	{
		global $pdo;
		global $mysql;
		global $text;
		global $interface_language;
		$messages = [];

		$statement_info = $pdo -> prepare('select b_code, title, table_name from b_shelf where b_code = :b_code');
		$statement_info -> execute(['b_code' => $bfy_b_code]);

		$info_row = $statement_info -> fetch();

		/*$statement_bfy = $pdo -> prepare('select b_code, book,  case when bt.shorttitle is not null then bt.shorttitle else t.book end `shorttitle`, chapter 
								from bible_for_a_year 
								left join book_titles bt on bible_for_a_year.book = bt.book and language_code = "' . $interface_language . '"
								where month = :month and day = :day and b_code = :b_code and year is not null');
		$result_bfy = $statement_bfy -> execute(['day' => $date -> format('j'), 'month' => $date -> format('n'), 'b_code' => $bfy_b_code]);
		*/
		/*

		// Deprecated

		if(!$result_bfy)
		{
			$messages[] = ['type' => 'danger', 'message' => $text['timetable_exception']];
			log_msg(__FILE__ . ':' . __LINE__ . ' Timetable PDO query exception. Info = ' . json_encode($statement_bfy -> errorInfo()) . ', $_REQUEST = ' . json_encode($_REQUEST));
		}
			// if Bible does not have BfaY timetable system creates it
			if ($statement_bfy -> rowCount() === 0)
			{
				// create new timetable for this $b_code
				$statement_chapters = $pdo -> prepare('select distinct(concat(book, "-", chapter)) ' 
														. 'from ' . $info_row['table_name']);
				$result_chapters = $statement_chapters -> execute();
				$chapters_amount = $statement_chapters -> rowCount();

				if (!$result_chapters or ($chapters_amount === 0))
				{
					$messages[] = ['type' => 'danger', 'message' => $text['timetable_exception']];
					log_msg(__FILE__ . ':' . __LINE__ . ' Timetable PDO query exception. Info = ' . json_encode($statement_chapters -> errorInfo()) . ', $_REQUEST = ' . json_encode($_REQUEST) . ', statement = ' . json_encode($statement_chapters));
				}

				$tt_date = new DateTime('2001-01-01');
				$days_left = 365;
				$interval = new DateInterval('P1D');

				$insert_timetable_query = 'insert into bible_for_a_year (b_code, book, chapter, month, day) values ';
				while(($chapters_amount > 0) or ($days_left > 0))
				{
					$chapters_for_a_day = floor($chapters_amount / $days_left);

					for($i = 1; $i <= $chapters_for_a_day; $i++)
					{
						$row = $statement_chapters -> fetch();
						$array = explode('-', $row[0]);
						$tt_book = $array[0];
						$tt_chapter = $array[1];
						$insert_timetable_query .= '("' . $bfy_b_code . '", "' . $tt_book . '", ' . $tt_chapter . ', ' . $tt_date -> format('n') . ', ' . $tt_date -> format('j') . '),';
					}
					$days_left--;
					$tt_date -> add($interval);
					$chapters_amount -= $chapters_for_a_day;
				}
				$insert_timetable_query[strlen($insert_timetable_query)-1] = ';';
				$insert_result = mysqli_query($mysql, $insert_timetable_query);

				if (!$insert_result)
				{
					$messages[] = ['type' => 'danger', 'message' => $text['timetable_create_def_tt_exception']];
					log_msg(__FILE__ . ':' . __LINE__ . ' Timetable PDO query exception. Info = "' . mysqli_error($mysql) . '", $_REQUEST = ' . json_encode($_REQUEST));
				}
			} // </bfy creating>
			*/
			// Daily Readings checking

			require './lib/create_daily_reading.php';
						
						/*

		$statement_bfy = $pdo -> prepare('select b_code, book,  case when bt.shorttitle is not null then bt.shorttitle else t.book end `shorttitle`, chapter 
								from bible_for_a_year 
								left join book_titles bt on bible_for_a_year.book = bt.book and language_code = "' . $interface_language . '"
								where month = :month and day = :day and b_code = :b_code and year is not null');
		$result_b
						*/
			// display today readings

			$statement_bfy = $pdo -> prepare('select bfy.b_code, bfy.book,   case when bt.shorttitle is not null then bt.shorttitle else bfy.book end `shorttitle`, bfy.chapter 
				from bible_for_a_year bfy
				left join book_titles bt on bfy.book = bt.book and bt.language_code = "' . $interface_language . '"
				where bfy.`month` = :month and bfy.`day` = :day and bfy.b_code = :b_code and bfy.year is not null');
			echo '<br /><h3>' . $text['bible_for_a_year'] . '</h3> ' . $info_row['title'] . '<br />';
			$result_bfy = $statement_bfy -> execute(['day' => $date -> format('j'), 'month' => $date -> format('n'), 'b_code' => $bfy_b_code]);

			if(!$result_bfy)
			{
				$messages[] = ['type' => 'danger', 'message' => $text['timetable_exception']];
				log_msg(__FILE__ . ':' . __LINE__ . ' Timetable PDO query exception. Info = ' . json_encode($statement_bfy -> errorInfo()) . ', $_REQUEST = ' . json_encode($_REQUEST));
			}
			else
			{
				$chapters_rows = $statement_bfy -> fetchAll();
				echo '<br />';
				foreach ($chapters_rows as $row) 
				{
					$statement_schedule = $pdo -> prepare('select scheduled from bible_for_a_year_schedules bfys 
															where user_id = :user_id and b_code = :b_code');
					$result = $statement_schedule -> execute(['user_id' => $_SESSION['uid'], 'b_code' => $bfy_b_code]);
					$messages[] = check_result($result, $statement_schedule, $text['tt_schedules_exception'],  __FILE__ . ':' . __LINE__ . ' Schedule selection exception');
					if ($result)
					{
						if ($statement_schedule -> rowCount() == 0)
							$scheduled = FALSE;
						else
						{
							$schedule_row = $statement_schedule -> fetch();
							if (is_null($schedule_row['scheduled']))
								$scheduled = FALSE;
							else
								$scheduled = true;
						}

						$statement_schedule = $pdo -> prepare('select bfyr.read from bible_for_a_year_readings bfyr
																join bible_for_a_year_schedules bfys on bfyr.schedule_id = bfys.id
																where bfys.user_id = :user_id and book = :book and chapter = :chapter');
						$result = $statement_schedule -> execute(['book' => $row['book'], 'chapter' => $row['chapter'], 'user_id' => $_SESSION['uid']]);
						$message = $messages[] = check_result($result, $statement_schedule, $text['tt_readings_exception'],  __FILE__ . ':' . __LINE__ . ' Readings selection exception');
						if ($result)
						{
							$schedule_row = $statement_schedule -> fetch();
							if ($statement_schedule -> rowCount() === 0)
								$read = 0;
							else
								$read = (new DateTime($schedule_row['read'])) -> format('Y');
							$alert = '';
							if ($scheduled and $read >= $date -> format('Y'))
								$alert = ' class="alert alert-success"';
							elseif ($scheduled)
							{
								if (in_array($row['book'], ['MAT', 'MRK', 'MAR', 'LUK', 'JHN', 'JOH']))	
									$alert = ' class="alert alert-danger"';
								else
									$alert = ' class="alert alert-warning"';
							}
					
							echo '<a href="./?menu=bible&b_code=' . $bfy_b_code . '&book=' . $row['book'] . '&chapter=' . $row['chapter'] . '" target="_blank"><span' . $alert . '><b>' . $row['shorttitle'] .'</b> ' . $row['chapter'] . '</span></a> ';
						}
					
					}
				}
			//}
		}
		return $messages;
	}

	require './lib/create_daily_reading.fun.php';
	require './lib/timezones.php';
	require './lib/alerts.php';

?>