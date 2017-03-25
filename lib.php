<?php

	function html_verse($verse_row)
	{
		global $pdo;
		global $mysql;
		global $base_url;
		global $text;
		global $menu;

		global $b_code;
		global $book;
		global $chapter;
		global $verse;


		$html_verse = '';

		if ($verse_row['startVerse'] == $verse)
		{
			$b_start = '<b>';
			$b_end = '</b>';
		}
		else
		{
			$b_start = '';
			$b_end = '';
		}
		if (strpos($verse_row['verseText'], '¶') !== FALSE)
		{
			$html_verse .= '<br />';
			$verse_row['verseText'] = str_replace('¶', '', $verse_row['verseText']);
		}
		// fav = glyphicon glyphicon-heart
		$first_words = substr($verse_row['verseText'], 0, 90) ;
		if (strlen($verse_row['verseText']) > 90) $first_words .= '...';

		$verse_paragraph_title = $text['click_to_share'];
			if ($_SESSION['uid'] > -1)
				$verse_paragraph_title .= $text['add_to_fav_addition'];
			$verse_paragraph_title .= $text['copy_link_to_verse'] . '.';		

		$html_verse .= '<div class="dropdown">
						<p class="dropdown-toggle" title="' . $verse_paragraph_title . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" id="' . $verse_row['verseID'] . '" align="justify">'; 
		if (strcasecmp($menu, 'search') !== 0)
			$html_verse .= '<sup>' . $verse_row['startVerse'] . '</sup> ';

		$html_verse .= $b_start . $verse_row['verseText'] . $b_end . '</p><ul class="dropdown-menu" aria-labelledby="' . $verse_row['verseID'] . '">';

		if ($_SESSION['uid'] > - 1)
			 $html_verse .= '<li><a href="./?menu=users_addVerseToFavorites&b_code=' . $b_code . '&id=' . $verse_row['verseID'] . '" target="_blank"><span class="glyphicon glyphicon-heart"></span> Add To Favorites</a></li>';

		$url = $base_url . '?b_code=' . $b_code . '&book=' . $verse_row['book'] . '&chapter=' . $chapter . '&verse=' . $verse_row['startVerse'] . '#' . $verse_row['verseID'];

		// Facebook Share
		$html_verse .= '<li><div class="fb-share-button" data-href="%SHARE_URL%" data-layout="button" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url) . '&src=sdkpreparse">Share</a></div></li>';

		// Facebook Like

		$html_verse .= '<li><div class="fb-like" data-href="' . $url . '" data-layout="standard" data-action="like" data-show-faces="true" data-share="false"></div></li>';

		// VK Share Link

		$html_verse .= '<li><a href="http://vk.com/share.php?url=' . urlencode($url) . '" target="_blank"><span class="glyphicon glyphicon-share"></span> ' . $text['share_in_vk'] . '</a></li>';

		// VK Share https://vk.com/editapp?act=create
		/*$html_verse .= '<a href="http://vk.com/share.php?url=' . urlencode($url) . '" target="_blank">Share in VK</a><br />';
		*/
		// VK Like https://vk.com/editapp?act=create
		/*$html_verse .= '<div id="vk_like_' . $verse_row['verseID'] . '"></div>
					<script type="text/javascript">
					VK.init({apiId: 111, onlyWidgets: true});
					 VK.Widgets.Like(\'vk_like_' . $verse_row['verseID'] . '\', {pageUrl: \'' . $url .'\', pageTitle: \'' . $bible_title . ' ' . $verse_row['book'] . ' ' . $chapter . ':' . $verse_row['startVerse'] . '\'}, \'' . $b_code . $verse_row['verseID'] . '\');</script>';
		*/

		// Twitter 
		$html_verse .= '<li><a href="./?menu=tweetVerse&id=' . $verse_row['verseID'] . '&b_code=' . $b_code . '&book=' . $verse_row['book'] . '&chapter=' . $chapter . '&verseNumber=' . $verse_row['startVerse'] . '&first_words=' . $first_words . '" target="_blank"><span class="glyphicon glyphicon-comment"></span> ' . $text['text_tweet'] . '</a></li>';



		$html_verse .= '<li><a onclick="clipboard.copy(window.location.origin + window.location.pathname + \'?b_code=' 
			. $b_code . '&book=' . $verse_row['book'] . '&chapter=' . $chapter . 
			'&verse=' . $verse_row['startVerse'] . '#'. $verse_row['verseID'] . '\')"><span class="glyphicon glyphicon-copy"></span> ' . $text['copy_link_to_the_verse'] . '</a></li>'
			. '</ul></div>' . PHP_EOL;

		return $html_verse;
	}

	function display_bfy_schedule($bfy_b_code, $date)
	{
		global $pdo;
		global $mysql;
		global $text;

		$messages = [];

		$statement_info = $pdo -> prepare('select b_code, title, table_name from b_shelf where b_code = :b_code');
		$statement_info -> execute(['b_code' => $bfy_b_code]);

		$info_row = $statement_info -> fetch();

		$statement_bfy = $pdo -> prepare('select b_code, book, chapter from bible_for_a_year where month = :month and day = :day and b_code = :b_code');
		$result_bfy = $statement_bfy -> execute(['day' => $date -> format('j'), 'month' => $date -> format('n'), 'b_code' => $bfy_b_code]);

		if(!$result_bfy)
		{
			$messages[] = ['type' => 'danger', 'message' => $text['timetable_exception']];
			log_msg(__FILE__ . ':' . __LINE__ . ' Timetable PDO query exception. Info = ' . json_encode($statement_bfy -> errorInfo()) . ', $_REQUEST = ' . json_encode($_REQUEST));
		}
		else
		{
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
			}
			
			// display today readings
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
					$messages[] = check_result($result, $statement_schedule, $text['tt_schedules_exception'], 'Schedule selection exception');
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
						$message = $messages[] = check_result($result, $statement_schedule, $text['tt_readings_exception'], 'Readings selection exception');
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
								$alert = ' class="alert alert-warning"';

							echo '<a href="./?menu=bible&b_code=' . $bfy_b_code . '&book=' . $row['book'] . '&chapter=' . $row['chapter'] . '" target="_blank"><span' . $alert . '><b>' . $row['book'] .'</b> ' . $row['chapter'] . '</span></a> ';
						}
					
					}
				}
			}
		}
		return $messages;
	}


	function set_user_timezone($datetime)
	{
		$user_date = new DateTime($datetime -> format('Y-m-d H:i:s'));
		$timezone = new DateTimezone($_SESSION['timezone']);
		$user_date -> setTimezone($timezone);
		return $user_date;
	}

?>