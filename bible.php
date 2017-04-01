<?php
	require 'bible_nav.php';

	// just interesting random =] 1 to 1000 that you will get 'Hallelujah!' text =]
	$b_code = $userBible -> b_code;
	$random = rand(1, 1000);
	if ($random === 1000)
		echo '<p class="alert alert-success">' . $text['hallelujah'] . '</p>';

	$statement_translation = $links['sofia']['pdo'] -> prepare(
			'select bh.b_code, bh.table_name, bh.title, bh.description, bh.copyright, 
			bh.license, l.link, bh.http_link, country, language, dialect
			from b_shelf bh 
			join licenses l on l.license = bh.license
			where b_code = :b_code'
		);


	$result_translation = $statement_translation -> execute(array('b_code' => $b_code));
	if(!$result_translation)
		log_msg(__FILE__ . ':' . __LINE__ . ' PDO translations query exception. Info = {' . json_encode($statement_translation -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}' );
	$info_row = $statement_translation -> fetch();
	$bible_title = $info_row['title'];
	$bible_description = $info_row['description'];

	if (stripos($b_code, 'http') === FALSE)
	{
?>
<form method="post" class="hidden-print">
	<input type="hidden" name="menu" value="search" />
	<input type="hidden" name="search_in" value="<?=$b_code;?>" />
	<div class="input-group input-group-sm">
		<input type="text" class="form-control" name="search_query" placeholder="<?=$text['search_in'].$info_row['title'];?>" />
		<span class="input-group-btn"><input type="submit" class="btn btn-default" name="submit" value="<?=$text['text_search'];?>"></span>
	</div>
</form>
<br />
<?php
	}
?>
	<div class="panel panel-primary">
		<div>
		<?php

		if (stripos($b_code, '_http') === FALSE)
		{
			$table_name = $info_row['table_name'];

			$statement_books = $links['sofia']['pdo'] -> prepare('
								select distinct t.book, case when bt.shorttitle is not null then bt.shorttitle else t.book end `shorttitle` from ' . $table_name . ' t '.
								'left join book_titles bt on t.book = bt.book and language_code = "' . $userBible -> language_code . '"'
							);
			//log_msg(__FILE__ . ':' . __LINE__ . ' $userBible -> language_code = ' . $userBible -> language_code);
			$result = $books_result = $statement_books -> execute();
			if (!$result)
			{
				display_message(['type' => 'danger', 'message' => $text['book_titles_exception']]);
				log_msg(__FILE__ . ':' . __LINE__ . ' Select book for titles. Exception. Info = {' . json_encode($statement_books -> errorInfo()) . '}, REQUEST = {' .json_encode($_REQUEST) . '}');
			}
			$books_rows = $statement_books -> fetchAll();
			$books_nav = '<div width="80%" align="center" class="hidden-print">';
			$books_form = '<form method="post" name="bookSelectionFormFromChosenBible" class="hidden-print">
							<input type="hidden" name="b_code" value="' . $b_code . '">
							<select name="book" class="form-control" onchange="bookSelectionFormFromChosenBible.submit()">
							'
							;

			$book_title = $book;
			$found_book = FALSE;
			foreach ($books_rows as $row) 
			{
				$books_nav .=  '<a href="./?b_code=' . $b_code . '&book=' . $row['book'] . '">[' . $row['shorttitle'] . ']</a> ';
				$selected = '';
				if (!$found_book and (strcasecmp($book, $row['book']) === 0) )
				{
					$selected = ' selected="selected"';
					$found_book = true;
					$book_title = $row['shorttitle'];
				}
				$books_form .= '<option value="' . $row['book'] . '"' . $selected . '>' . $row['shorttitle'] . '</option>';
			}
				if (!$found_book)
				$book = $books_rows[0]['book'];

				$books_form .= '</select></form>';
				$books_nav .= '</div>';

		?>
			<div><center><h2 id="bibleTitle" title="<?=$bible_description;?>"><?=$bible_title;?></h2></center></div>
			<nav class="gb-books-nav hidden-print"><?=$books_nav.'<br/>'.$books_form;?></nav>
		<?php
			if (!isset($book))
				$book = $books_rows[0]['book'];

			if (!isset($chapter))
				$chapter = 1;

			$statement_chapters = $links['sofia']['pdo'] -> prepare (
									'select distinct chapter from ' . $table_name 
									.' where book = :book'
								);
				$result_chapters = $statement_chapters -> execute(array('book' => $book));

				if(!$result_chapters)
					log_msg(__FILE__ . ':' . __LINE__ . ' PDO chapters query exception. Info = {' . json_encode($statement_chapters -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}, \$table_name = `' . $table_name . '`.' );

				$chapters_rows = $statement_chapters -> fetchAll();
				$chapters_links = '<div width="80%" align="center" class="hidden-print">';
				$chapter_count=0;

				$chapters_form = '<form method="post" name="chapterSelectionFormFromChosenBible">
							<input type="hidden" name="b_code" value="' . $b_code . '">
							<input type="hidden" name="book" value="' . $book . '">
							<select name="chapter" class="form-control" onchange="chapterSelectionFormFromChosenBible.submit()">
							'
							;

				foreach ($chapters_rows as $chapter_row) 
				{
					$chapter_count++;
					$chapters_links .= '<a href="./?b_code=' . $b_code . '&book=' . $book . '&chapter=' . $chapter_row['chapter'] . '">[' . $chapter_row['chapter'] . ']</a> ';
					$selected = '';
					if ($chapter == $chapter_row['chapter'])
						$selected = ' selected="selected"';
					$chapters_form .= '<option value="' . $chapter_row['chapter'] . '"' . $selected . '>' . $chapter_row['chapter'] . '</option>';
				}
				$chapters_links .= '</div>';
				$chapters_form .= '</select></form>';

				$chapter_nav = '<table width="100%"><tr><td width="50%">';
				if ($chapter > 1)
					$chapter_nav .= '<a href="./?b_code=' . $b_code . '&book=' . $book . '&chapter=' . ($chapter - 1) . '#top-anchor"><button class="btn btn-default">' . $text['previous_chapter'] . '</button></a>';
				$chapter_nav .= '</td><td width="50%" align="right">';
				if ($chapter < $chapter_count)
					$chapter_nav .= '<a href="./?b_code=' . $b_code . '&book=' . $book . '&chapter=' . ($chapter + 1) . '#top-anchor"><button class="btn btn-default">' . $text['next_chapter'] . '</button></a>';
				$chapter_nav .= '</td></tr></table>'; 

		?>
			<div id="book-title"><center><h3><?=$book_title;?> <?=$chapter;?></h3></center></div>
			<nav class="gb-pagination hidden-print"><?=$chapters_links.'<br/>'.$chapters_form;?></nav><br />
			<nav class="gb-chapter-nav hidden-print"><?=$chapter_nav;?></nav>

		</div>
		<?php

			$statement_verses = $links['sofia']['pdo'] -> prepare (
						'select * from ' . $table_name .
						' where book = :book and chapter = :chapter'
				);
			$result_verses = $statement_verses -> execute(array('book' => $book, 'chapter' => $chapter));

			$verse_paragraph_title = $text['click_to_share'];
			if ($_SESSION['uid'] > -1)
				$verse_paragraph_title .= $text['add_to_fav_addition'];
			$verse_paragraph_title .= $text['copy_link_to_verse'] . '.';		

			if(!$result_verses)
				log_msg(__FILE__ . ':' . __LINE__ . ' ' . ' PDO verses query exception. Info = {' . json_encode($statement_verses -> errorInfo())  . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}, \$table_name = `' . $table_name . '`.');

			$verses_rows = $statement_verses -> fetchAll();
			$verses = '';
			foreach ($verses_rows as $verse_row) 
			{
				$verses .= html_verse($verse_row);
			}

			// set this chapter as read if scheduled
			if ($_SESSION['uid'] > -1)
			{

				// Bibles for a year
				$statement_schedule = $pdo -> prepare('select scheduled, id from bible_for_a_year_schedules bfys 
														where user_id = :user_id and b_code = :b_code');
				$result = $statement_schedule -> execute(['user_id' => $_SESSION['uid'], 'b_code' => $b_code]);
				$message = check_result($result, $statement_schedule, $text['tt_schedules_exception'], 'Schedule selection exception');
				if (!empty($message))
					echo '<p class="alert alert-' . $message['type'] . '">' . $message['message'] . '</p>';

				if ($result)
				{
					$schedule_row = $statement_schedule -> fetch();
					if (!is_null($schedule_row['scheduled']))
					{
						$user_date = new DateTime();
						$user_date = set_user_timezone($user_date);

						$statement_timetable = $pdo -> prepare('select book, month, day from bible_for_a_year where b_code = :b_code and book = :book and chapter = :chapter and month = :month and day = :day');
						$result = $statement_timetable -> execute(['b_code' => $b_code, 'book' => $book, 'chapter' => $chapter, 'month' => $user_date -> format('n'), 'day' => $user_date -> format('j')]);

						$message = check_result($result, $statement_timetable, $text['timetable_exception'], ' Timetable `bible_for_a_year` was not opened');
						if (!empty($message))
							echo '<p class="alert alert-' . $message['type'] . '">' . $message['message'] . '</p>';

						if ($result and ($statement_timetable -> rowCount() > 0))
						{
							$statement_reading = $pdo -> prepare('insert into bible_for_a_year_readings (schedule_id, book, chapter, `read`) values (:schedule_id, :book, :chapter, :date);');
							$result = $statement_reading -> execute(['schedule_id' => $schedule_row['id'], 'book' => $book, 'chapter' => $chapter, 'date' => $user_date -> format('Y-m-d H:i:s')]);
							$message = check_result($result, $statement_reading, $text['tt_reading_update_exception'], 'Timetable reading update');
							if (!empty($message))
								echo '<p class="alert alert-' . $message['type'] . '">' . $message['message'] . '</p>';
						}
					}
				}

				// user's timetables

				$timetables_statement = $pdo -> prepare('select id, b_code from timetables where user_id = :user_id and b_code = :b_code');
				$result = $timetables_statement -> execute(['user_id' => $_SESSION['uid'], 'b_code' => $b_code]);
				$message = check_result($result, $timetables_statement, $text['tt_schedules_exception'], 'Timetables select PDO query exception.');
				if (!empty($message))
					echo '<p class="alert alert-' . $message['type'] . '">' . $message['message'] . '</p>';

				if ($result)
				{
					$timetables_rows = $timetables_statement -> fetchAll();
					foreach ($timetables_rows as $timetable_row)
					{
						// set book as read 
						$update_query = 'update schedules set `read` = now() where timetable_id = :timetable_id and book = :book and chapter = :chapter';
						$update_statement = $pdo -> prepare($update_query);
						$result = $update_statement -> execute(['timetable_id' => $timetable_row['id'], 'book' => $book, 'chapter' => $chapter]);
						$message = check_result($result, $update_statement, $text['tt_reading_update_exception'], __FILE__ . ':' . __LINE__ . ' Update reading exception.');
						if (!empty($message))
							echo '<p class="alert alert-' . $message['type'] . '">' . $message['message'] . '</p>';
					}
				}
			}
		?>
		<div class="panel-body"><?=$verses;?></div>

	<nav class="gb-chapter-nav hidden-print"><?=$chapter_nav;?></nav><br />
	<nav class="gb-pagination hidden-print"><?=$chapters_links;?></nav><br />
	<nav class="gb-books-nav hidden-print"><?=$books_nav;?></nav>

	<?php
		}
		else
		{
	?>	
		<script type="text/javascript">$(document).ready(function (){resize();});</script>
		<div class="panel-body"><center><iframe src="<?=$info_row['http_link'];?>" width="80%" id="BibleFrame" name="BibleFrame" onresize="alert('resize');resize();"></iframe></center></div>
	<?php
		}
	?>


		<div class="panel-footer">
			<center><h5><b><?=$info_row['title'];?></b></h5><br /><?=$info_row['copyright'];?><br /><?=$text['published_under'];?> <a href="<?=$info_row['link'];?>" target="_blank"><?=$info_row['license'];?></a></center>
		</div>
	</div>
	<?php
		if (stripos($b_code, '_http') === FALSE)
		{	
			echo '<p style="font-size: 0.75em">' . $verse_paragraph_title . '</p>';
		}

		if ($_SESSION['uid'] > -1)
		{
			$bfy_scheduled = FALSE;


			$statement_schedules = $pdo -> prepare('select user_id, b_code, scheduled from bible_for_a_year_schedules where user_id = :user_id and scheduled is not null');
			$statement_schedules -> execute(['user_id' => $_SESSION['uid']]);
			$schedules_rows = $statement_schedules -> fetchAll();

			foreach ($schedules_rows as $schedule_row) 
			{
				if (strcasecmp($schedule_row['b_code'], $b_code) === 0)
				{
					$bfy_scheduled = true;
					break;
				}
			}

			if (!$bfy_scheduled)
				echo '<form method="post" class="hidden-print">
							<input type="hidden" name="menu" value="timetable">
							<input type="hidden" name="action" value="schedule">
							<input type="submit" class="btn btn-default form-control" name="submit" value="' . $text['to_schedule'] . '"></form>';
			else
				echo '<a class="hidden-print" href="./?menu=timetable"><button class="btn btn-default form-control">' . $text['text_timetable'] . '</button></a>';
		} // </if-not-guest>
		
		echo '<br /><br /><br /><div class="hidden-print"><p>' . $text['use_ctrl_s'] . '</p>';
		echo '<p>' . $text['use_ctrl_p'] . '</p></div>';
	?>