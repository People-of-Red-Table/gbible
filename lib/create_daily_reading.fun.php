<?php

	function create_daily_reading($b_code)
	{
		global $pdo;
		global $mysql;
		global $text;
		global $date;

		if (strpos($b_code, '_http') !== FALSE)
			return;

		$statement_info = $pdo -> prepare('select b_code, title, table_name from b_shelf where b_code = :b_code');
		$statement_info -> execute(['b_code' => $b_code]);

		$info_row = $statement_info -> fetch();


		$statement_books = $pdo -> prepare('select distinct book from ' . $info_row['table_name']);
		$result = $statement_books -> execute();

		$message = check_result($result, $statement_books, $text['please_contact_support'], __FILE__ . ':' . __LINE__ . ' Selecting books exception.');

		$books = [];
		if ($result)
		{
			while ($book_row = $statement_books -> fetch())
			{
				$books[] = $book_row['book'];
			}
			$old_testament = [];
			$index = 0;
			foreach ($books as $item)
			{
				$index++;
				if (in_array($item, ['MAT', 'MRK', 'LUK', 'JHN', 'ACT']))
					break;
				$old_testament[] = $item;
			}
			$nt_after_gospels = [];
			for($i = $index; $i < count($books); $i++)
			{
				if (in_array($books[$i], ['MAT', 'MRK', 'LUK', 'JHN']))
					continue;
				$nt_after_gospels[] = $books[$i];
			}


			
			if ((stripos($b_code, 'rus') === 0)
				or (stripos($b_code, 'bel') === 0)
				or (stripos($b_code, 'ukr') === 0)
				)
				$from_date = new DateTime($date->format('Y') . '-01-07');
			else
			{
				if (($date -> format('n') < 11)
					or ( ($date -> format('n') == 12) and ($date->format('j') < 25)))
					$from_date = new DateTime( ($date->format('Y')-1) . '-12-25');
				else
					$from_date = new DateTime($date->format('Y') . '-12-25');
			}

			$to_date = new DateTime($from_date->format('Y-m-d'));
			$to_date->add(new DateInterval("P1Y"));
			$to_date->sub(new DateInterval("P1D"));

		    $easter_date = new DateTime($date -> format('Y') . '-03-21');
		    $days = easter_days($date -> format('Y'));
			$easter_date->add(new DateInterval("P{$days}D"));

			//Christmas day: first chapters of Matthew and Luke.
			$timetable_rows = [];
			$timetable_rows[] = 
									['year' => $from_date->format('Y'), 
									'month' => $from_date->format('n'), 
									'day' => $from_date->format('j'), 
									'book' => 'MAT',
									'chapter' => 1]
							  ;

			$timetable_rows[] = ['year' => $from_date->format('Y'), 
									'month' => $from_date->format('n'), 
									'day' => $from_date->format('j')-1, 
									'book' => 'LUK',
									'chapter' => 1];									  
			$timetable_rows[] = ['year' => $from_date->format('Y'), 
									'month' => $from_date->format('n'), 
									'day' => $from_date->format('j'), 
									'book' => 'LUK',
									'chapter' => 2];


			//Next days after Christmas next chapters and chapters from Mark and John.
			$good_friday_date = new DateTime($easter_date->format('Y-m-d'));
			$good_friday_date -> sub(new DateInterval("P2D"));

			$next_after_xmas = new DateTime($from_date->format('Y-m-d'));
			$next_after_xmas -> add(new DateInterval("P1D"));
			$day_before_GF = new DateTime($good_friday_date->format('Y-m-d'));
			$day_before_GF -> sub(new DateInterval("P1D"));
			$good_friday_diff = $next_after_xmas -> diff($day_before_GF);
			$good_friday_days = $good_friday_diff -> days;

			$ranges = [['book' => 'MAT', 'from' => 2, 'to' => 26],
						['book' => 'MRK', 'from' => 1, 'to' => 14],
						['book' => 'LUK', 'from' => 3, 'to' => 21],
						['book' => 'JHN', 'from' => 1, 'to' => 17]];

			foreach ($ranges as $item)
			{
				$dt_book = $item['book']; $dt_from = $item['from']; $dt_to = $item['to'];
				$for_every = floor($good_friday_days / ($dt_to - $dt_from));
				$dt = new DateTime($from_date -> format('Y-m-d'));
				$dt -> add(new DateInterval("P1D"));

				foreach (range($dt_from, $dt_to) as $dt_chapter) 
				{
					
					$timetable_rows[] = ['year' => $dt->format('Y'), 
											'month' => $dt->format('n'), 
											'day' => $dt->format('j'), 
											'book' => $dt_book,
											'chapter' => $dt_chapter];

					$dt -> add(new DateInterval("P{$for_every}D"));
				}
			}

			//Good Friday: Matthew 27, Mark 15, Luke 22 23, John 18 19

			$timetable_rows[] = ['year' => $good_friday_date->format('Y'), 
									'month' => $good_friday_date->format('n'), 
									'day' => $good_friday_date->format('j'), 
									'book' => 'MAT',
									'chapter' => 27];
			$timetable_rows[] = ['year' => $good_friday_date->format('Y'), 
									'month' => $good_friday_date->format('n'), 
									'day' => $good_friday_date->format('j'), 
									'book' => 'MRK',
									'chapter' => 15];
			$timetable_rows[] = ['year' => $good_friday_date->format('Y'), 
									'month' => $good_friday_date->format('n'), 
									'day' => $good_friday_date->format('j'), 
									'book' => 'LUK',
									'chapter' => 22];
			$timetable_rows[] = ['year' => $good_friday_date->format('Y'), 
									'month' => $good_friday_date->format('n'), 
									'day' => $good_friday_date->format('j'), 
									'book' => 'LUK',
									'chapter' => 23];
			$timetable_rows[] = ['year' => $good_friday_date->format('Y'), 
									'month' => $good_friday_date->format('n'), 
									'day' => $good_friday_date->format('j'), 
									'book' => 'JHN',
									'chapter' => 18];
			$timetable_rows[] = ['year' => $good_friday_date->format('Y'), 
									'month' => $good_friday_date->format('n'), 
									'day' => $good_friday_date->format('j'), 
									'book' => 'JHN',
									'chapter' => 19];

			//Easter: Matthew 28, Mark 16, Luke 24, John 20 21

			$timetable_rows[] = ['year' => $easter_date->format('Y'), 
									'month' => $easter_date->format('n'), 
									'day' => $easter_date->format('j'), 
									'book' => 'MAT',
									'chapter' => 28];
			$timetable_rows[] = ['year' => $easter_date->format('Y'), 
									'month' => $easter_date->format('n'), 
									'day' => $easter_date->format('j'), 
									'book' => 'MRK',
									'chapter' => 16];
			$timetable_rows[] = ['year' => $easter_date->format('Y'), 
									'month' => $easter_date->format('n'), 
									'day' => $easter_date->format('j'), 
									'book' => 'LUK',
									'chapter' => 24];
			$timetable_rows[] = ['year' => $easter_date->format('Y'), 
									'month' => $easter_date->format('n'), 
									'day' => $easter_date->format('j'), 
									'book' => 'JHN',
									'chapter' => 20];
			$timetable_rows[] = ['year' => $easter_date->format('Y'), 
									'month' => $easter_date->format('n'), 
									'day' => $easter_date->format('j'), 
									'book' => 'JHN',
									'chapter' => 21];


			//Next days after Easter Acts and next books.

			$insert_query = 'insert into bible_for_a_year (b_code, `year`, `month`, `day`, book, chapter) values ';

			// inserting Gospels
			foreach ($timetable_rows as $timetable_row) 
			{
				// [!] $timetable_rows is preconstructed array here
				$insert_query .= '("' . $b_code . '", ' . $timetable_row['year'] . ', ' . $timetable_row['month'] . ', ' . $timetable_row['day'] . ', "' . $timetable_row['book'] . '", ' . $timetable_row['chapter'] . '),';
			}
			$insert_query[strlen($insert_query)-1] = ' ';
			$result = mysqli_query($mysql, $insert_query);

			if (!$result)
			{
				$message = ['type' => 'danger', 'message' => $text['please_contact_support']];
				log_msg(__FILE__ . ':' . __LINE__ . ' Inserting gospels exception.');
				display_message($message);
			}

			// NT after gospels...
			// $nt_after_gospels

			$ntag_str = implode('", "', $nt_after_gospels);
			$ntag_str = '"' . $ntag_str;
			$ntag_str .= '"';
			$select_query = 'select distinct book, chapter from ' . $info_row['table_name'] 
							. ' where book in (' . $ntag_str . ');';
			$select_chapters_statement = $pdo -> prepare($select_query);
			$result = $select_chapters_statement -> execute();
			$message = check_result($result, $select_chapters_statement, $text['please_contact_support'], __FILE__ . ':' . __LINE__ . ' Selecting chapters exception.');



			if ($result)
			{
				$chapters_amount = $select_chapters_statement -> rowCount();
				$next_after_easter_date = new DateTime($easter_date -> format('Y-m-d'));
				$next_after_easter_date -> add(new DateInterval("P1D"));
				$interval = $to_date -> diff($next_after_easter_date);

				$dt = new DateTime($next_after_easter_date -> format('Y-m-d'));

				$chapters_left = $chapters_amount;
				$for_every = floor($interval -> days / ($chapters_amount));

				$insert_query = 'insert into bible_for_a_year (b_code, `year`, `month`, `day`, book, chapter) values ';

				while ($row = $select_chapters_statement -> fetch())
				{
					if (!empty($row['book']))
					{
						$insert_query .= '("' . $b_code . '", ' . $dt -> format('Y') . ', ' . $dt->format('n') . ', ' . $dt->format('j') . ', "' . $row['book'] . '", ' . $row['chapter'] . '),';
						$dt -> add(new DateInterval("P{$for_every}D"));
					}
				}

				$insert_query[strlen($insert_query)-1] = ' ';
				$result = mysqli_query($mysql, $insert_query);
				if (!$result)
				{
					$message = ['type' => 'danger', 'message' => $text['please_contact_support']];
					log_msg(__FILE__ . ':' . __LINE__ . ' Inserting NT after Gospels exception.' . mysqli_error($mysql));
					display_message($message);
				}						

			}
			else display_message($message);

			
			// OT
			$ot_str = implode('", "', $old_testament);
			$ot_str = '"' . $ot_str;
			$ot_str .= '"';
			$select_query = 'select distinct book, chapter from ' . $info_row['table_name'] 
							. ' where book in (' . $ot_str . ');';
			$select_chapters_statement = $pdo -> prepare($select_query);
			$result = $select_chapters_statement -> execute();
			$message = check_result($result, $select_chapters_statement, $text['please_contact_support'], __FILE__ . ':' . __LINE__ . ' Selecting chapters exception.');

			$insert_query = 'insert into bible_for_a_year (b_code, `year`, `month`, `day`, book, chapter) values ';

			if ($result)
			{
				$chapters_amount = $select_chapters_statement -> rowCount();
				$interval = $to_date -> diff($from_date);
				$algo = 'c/d';
				$chapters_left = $chapters_amount;
				if ($interval -> days > $chapters_amount)
				{
					$algo = 'd/c';
					$for_every = floor($interval -> days / ($chapters_amount));
				}
				else
				{
					$days_left = $interval -> days;							
				}

				$dt = new DateTime($from_date -> format('Y-m-d'));
				$dt -> add(new DateInterval("P1D"));

				while ($chapters_left > 0)
				{
					if ($algo === 'c/d')
					{
						$chapters_for_a_day = floor($chapters_left / $days_left);
					}
					else
					{
						$chapters_for_a_day = 1;
					}
					$chapters_left -= $chapters_for_a_day;

					do
					{
						$row = $select_chapters_statement -> fetch();
						$insert_query .= '("' . $b_code . '", ' . $dt -> format('Y') . ', ' . $dt->format('n') . ', ' . $dt->format('j') . ', "' . $row['book'] . '", ' . $row['chapter'] . '),';
						$chapters_for_a_day--;
					}while ($chapters_for_a_day > 0);

					if ($algo === 'c/d')
					{
						$days_left--;
						$dt -> add(new DateInterval("P1D"));
					}
					else
					{
						$dt -> add(new DateInterval("P{$for_every}D"));
					}

				}

				$insert_query[strlen($insert_query)-1] = ' ';
				$result = mysqli_query($mysql, $insert_query);

				if (!$result)
				{
					$message = ['type' => 'danger', 'message' => $text['please_contact_support']];
					log_msg(__FILE__ . ':' . __LINE__ . ' Inserting OT exception. Info = ' . mysqli_error($mysql));
					display_message($message);
				}

			}
			else display_message($message);

		}
		else display_message($message);		
	}

?>