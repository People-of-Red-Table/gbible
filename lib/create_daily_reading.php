<?php
		$statement_bfy = $pdo -> prepare('select b_code, book, chapter from bible_for_a_year where `month` = :month and `day` = :day and `year` = :year and b_code = :b_code');
		$result_bfy = $statement_bfy -> execute(['day' => $date -> format('j'), 'month' => $date -> format('n'), 'year' => $date->format('Y'), 'b_code' => $bfy_b_code]);

		if(!$result_bfy)
		{
			$message = ['type' => 'danger', 'message' => $text['timetable_exception']];
			log_msg(__FILE__ . ':' . __LINE__ . ' Timetable PDO query exception. Info = ' . json_encode($statement_bfy -> errorInfo()) . ', $_REQUEST = ' . json_encode($_REQUEST));
			display_message($message);
		}
		else
		{
			if ($statement_bfy -> rowCount() === 0)
			{
				create_daily_reading($bfy_b_code);
			} /// </daily-readings-creating>
		}


?>