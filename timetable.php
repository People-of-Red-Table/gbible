<h1><?=$text['text_timetable'];?></h1>
<?php
	if (!isset($_REQUEST['date']))
	{
		$date = new DateTime();
		$date = set_user_timezone($date);
	}
	else $date = new DateTime($_REQUEST['date']);
	$messages = [];

	if(isset($_REQUEST['action']))
	{
		$statement_info = $pdo -> prepare('select b_code, title, table_name from b_shelf where b_code = :b_code');
		$statement_info -> execute(['b_code' => $tt_b_code]);

		$info_row = $statement_info -> fetch();
		switch ($_REQUEST['action'])
		{
			case 'schedule' : 
			{
				$statement_schedule = $pdo -> prepare('select id from bible_for_a_year_schedules where user_id = :user_id and b_code = :b_code');
				$result = $statement_schedule -> execute(['user_id' => $_SESSION['uid'], 'b_code' => $b_code]);
				if (!$result)
				{
					$messages[] = ['type' => 'danger', 'message' => $text['schedules_selection_exception']];
					log_msg(__FILE__ . ':' . __LINE__ . ' Schedule selection exception. Info = ' . json_encode($statement_schedule -> errorInfo()) . ', $_REQUEST = ' . json_encode($_REQUEST));
				}
				if ($statement_schedule -> rowCount() === 0)
				{
					$query = 'insert into bible_for_a_year_schedules (user_id, b_code, scheduled) values (:user_id, :b_code, now());';
				}
				else
				{
					$query = 'update bible_for_a_year_schedules set scheduled = now() where user_id = :user_id and b_code = :b_code';
				}
				$statement_schedule = $pdo -> prepare($query);
				$result = $statement_schedule -> execute(['user_id' => $_SESSION['uid'], 'b_code' => $b_code]);

				if (!$result)
				{
					$messages[] = ['type' => 'danger', 'message' => $text['scheduling_exception']];
					log_msg(__FILE__ . ':' . __LINE__ . ' Schedule selection exception. Info = ' . json_encode($statement_schedule -> errorInfo()) . ', $_REQUEST = ' . json_encode($_REQUEST));
				}
				break;
			}
			case 'unschedule' : 
			{
				$query = 'update bible_for_a_year_schedules set scheduled = null where user_id = :user_id and b_code = :b_code';
				$statement_schedule = $pdo -> prepare($query);
				$result = $statement_schedule -> execute(['user_id' => $_SESSION['uid'], 'b_code' => $_REQUEST['bfy_b_code']]);
				if (!$result)
				{
					$messages[] = ['type' => 'danger', 'message' => $text['scheduling_exception']];
					log_msg(__FILE__ . ':' . __LINE__ . ' Schedule selection exception. Info = ' . json_encode($statement_schedule -> errorInfo()) . ', $_REQUEST = ' . json_encode($_REQUEST));
				}
				break;
			}
			case 'submit_own_timetable':
			{
				if (stripos($tt_b_code, 'http') !== FALSE)
				{
					echo '<p class="alert alert-danger">' . $text['choose_not_h_bibles'] . $tt_b_code . '</p>';
					break;
				}
				$day_of_week = $_REQUEST['day_of_week'];
				$chapters_in = $_REQUEST['chapters_in'];	

				// create new timetable for this $b_code
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

				$insert_timetable_query = 'insert into timetables (user_id, title, b_code) values (:user_id, :title, :b_code);';

				$insert_statement = $pdo -> prepare($insert_timetable_query);
				$result = $insert_statement -> execute(['user_id' => $_SESSION['uid'], 'title' => $_REQUEST['timetable_title'], 'b_code' => $tt_b_code]);

				$messages[] = check_result($result, $insert_statement, $text['scheduling_exception'], '`timetables` insert PDO query exception.');
				if ($result)
				{
					$select_query = 'select max(id) from timetables where user_id = :user_id and b_code = :b_code';

					$select_statement = $pdo -> prepare($select_query);
					$result = $select_statement -> execute(['user_id' => $_SESSION['uid'], 'b_code' => $tt_b_code]);
					$messages[] = check_result($result, $insert_statement, $text['scheduling_exception'], '`timetables` select PDO query exception.');
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
				break;
			}
			case 'create_own_timetable':
			{
				?>
				<form method="post" name="countrySelectionForm">
	<input type="hidden" name="menu" value="timetable" />
	<input type="hidden" name="action" value="create_own_timetable" />
				<div class="form-group">
					<label for="countryOfCountrySelectionForm"><?=$text['text_country'];?></label>
					<select id="country-selection" class="bible-nav-select form-control" name="tt_country" onchange="document.countrySelectionForm.submit();" id="countryOfCountrySelectionForm">
							<?php
									$query = 'select distinct country `country_name`, 
												case when cnt.native is null then t.country else cnt.native end `country`
										from
										(select country from b_shelf 
										union all
										select country_name from iso_ms_languages
												where language_name in (select language_name from b_shelf)
										) as t
										left join countries cnt on t.country = cnt.name
										order by country';
									$result_countries = mysqli_query($links['sofia']['mysql'], $query);
									if (!$result_countries)
									{
										log_msg(__FILE__ . ':' . __LINE__ . ' 
											Countries SQL exception. Info = {' . mysqli_error($links['sofia']['mysql']) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
										//echo "Whoops. We've got issue with MySQL connection... Sorry. Please contact support. " . mysqli_error($links['sofia']['mysql']);;
										//print_r($links['sofia']['mysql']);

									}
									else
									{
										$statement_country = $links['sofia']['pdo'] -> prepare('select country_name `country` from iso_ms_languages where country_code = :country or country_name = :country');
										$result_country = $statement_country -> execute(array('country' => $ttBible -> country));
										//print_r($statement_country);
										if (!$result_country)
										{	
											log_msg(__FILE__ . ':' . __LINE__ . ' Countries PDO query exception. Info = {' . json_encode($statement_country -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
											//echo "Whoops. We've got issue with PDO connection... Sorry. Please contact support.";
											//print_r($statement_country -> errorInfo());
										}
										$country_row = $statement_country -> fetch();
										$country_name = strtolower($country_row['country']);
										while ($row = mysqli_fetch_assoc($result_countries)) 
										{
											$selected = '';
											//echo "<!-- `$country_name` ?= `" . strtolower($row['country']) . "` for `$country` -->";
											if (strcasecmp($country_name, $row['country_name']) === 0)
											{
												$selected = ' selected'; 
											}
											echo '<option value="' . $row['country_name'] . '"' . $selected . '>' . $row['country'] . '</option>';
										}
									}
							?>
						</select>
				</div>
				</form>

				<form method="post" name="languageSelectionForm">
					<input type="hidden" name="menu" value="timetable" />
					<input type="hidden" name="action" value="create_own_timetable" />
					<input type="hidden" name="tt_country" value="<?=$tt_country;?>" id="countryOfLanguageSelectionForm" />
				<div class="form-group">
						<label for="languageOfLanguageSelectionForm"><?=$text['text_language'];?></label>
						<select id="languageOfLanguageSelectionForm" class="form-control" name="tt_language" onchange="document.languageSelectionForm.tt_country.value = document.countrySelectionForm.tt_country.value; document.languageSelectionForm.submit();">
							<?php
									$statement_languages = $links['sofia']['pdo'] -> prepare(
											'
												select distinct t.language_name, case when iso_ms.native_language_name is null then t.language_name else iso_ms.native_language_name end `native_language_name` from
												(
													select distinct language `language_name` from b_shelf
													where country = :country_name
													union all
													select distinct language `language_name` from b_shelf
													where language in 
														(
															select language_name from iso_ms_languages
															where country_name = :country_name and language_name in (select distinct language from b_shelf)
														)
												) as t

											left join iso_ms_languages iso_ms on t.language_name = iso_ms.language_name
											order by native_language_name
										');
										$result_languages = $statement_languages -> execute(array('country_name' => $ttBible -> country));

									if (!$result_languages)
									{
										log_msg(__FILE__ . ':' . __LINE__ . ' Languages PDO query exception. Info = {' . json_encode($statement_languages -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
										//echo "Whoops. We've got issue with PDO connection... Sorry. Please contact support. ";
										//print_r($links['sofia']['pdo']);

									}
									else
									{
										$statement_language = $links['sofia']['pdo'] -> prepare('
											select language_name from iso_ms_languages where lower(language_code) = :language and language_name in (select distinct language from b_shelf)
											union
											select language_name from iso_ms_languages where language_name = :language and language_name in (select distinct language from b_shelf)
											union
											select language_name from iso_ms_languages where country_name = :country  and language_name in (select distinct language from b_shelf)
											union
											select language_name from iso_ms_languages where country_code = :country and language_name in (select distinct language from b_shelf)
											union
											select language `language_name` from b_shelf where language = :language
											union
											select language `language_name` from b_shelf where country = :country
											');
										$result = $statement_language -> execute(array('language' => $ttBible -> language, 'country' => $ttBible -> $country));
										if (!$result)
										{
											log_msg(__FILE__ . ':' . __LINE__ . ' Languages PDO query exception. Info = {' . json_encode($statement_language -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
											//echo "Whoops. We've got issue with PDO connection... Sorry. Please contact support.";
											//print_r($links['sofia']['pdo'] -> errorInfo());
										}
										$language_rows = $statement_language -> fetchAll();
										$language_name = '';
										foreach ($language_rows as $item) 
										{
											if (strcasecmp($item['language_name'], $ttBible -> language) === 0)
												$language_name = strtolower($item['language_name']);
										}
										if (empty($language_name))
											$language_name = strtolower($language_rows[0]['language_name']);

										echo "<script>var language_name ='$language_name';</script>";
										$found_language = FALSE;
										while ($row = $statement_languages -> fetch()) 
										{
											$selected = '';
											if (strcasecmp($language_name,  $row['language_name']) === 0)
											{
												$selected = ' selected="selected"';
												$found_language = true;
											}
											echo '<option value="' . $row['language_name'] . '"' . $selected . '>' . $row['native_language_name'] . '</option>';
										}
										/*if (!$found_language)
											echo "<script>document.languageSelectionForm.language.value = '" . $row['language_name']. "';document.languageSelectionForm.language.text = '" . $row['native_language_name']. "';</script>";*/
									}			
							?>			
						</select>
				</div> <!-- row -->
				</form>

				<form method="post" name="bibleSelectionForm">
					<input type="hidden" name="menu" value="timetable" />
					<input type="hidden" name="action" value="create_own_timetable" />
				<div class="form-group">
						<label for="bible-selection"><?=$text['text_bible'];?></label>
						<input type="hidden" name="tt_country" value="<?=$tt_country;?>" />
						<input type="hidden" name="tt_language" value="<?=$tt_language;?>" />
						<select id="bible-selection" class="form-control" name="tt_b_code" onchange="document.bibleSelectionForm.tt_country.value = document.countrySelectionForm.tt_country.value; bibleSelectionForm.tt_language.value = document.languageSelectionForm.tt_language.value; document.bibleSelectionForm.submit();">
							<?php
									$statement_bibles = $links['sofia']['pdo'] -> prepare(
											'select b_code, title from b_shelf where language = :language_name order by title');
										$result_bibles = $statement_bibles -> execute(array('language_name' => $ttBible -> language));

									if (!$result_bibles)
									{
										log_msg(__FILE__ . ':' . __LINE__ . ' Bibles PDO query exception. Info = {' . json_encode($statement_bibles -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
										//echo "Whoops. We've got issue with PDO connection... Sorry. Please contact support. ";
										//print_r($links['sofia']['pdo']);

									}
									else
									{
										while ($row = $statement_bibles -> fetch()) 
										{
											$selected = '';
											if (isset($tt_b_code) and (strcasecmp($tt_b_code, $row['b_code']) === 0) )
											{
												$selected = ' selected="selected"'; 
											}
											echo '<option value="' . $row['b_code'] . '"' . $selected . '>' . $row['title'] . '</option>';
										}
									}			
							?>
						</select>
				</div> <!-- row -->
				<div class="alert alert-info"><?=$text['choose_not_h_bibles'];?></div>
				<button class="form-control btn btn-primary" onclick="document.bibleSelectionForm.submit()"><?=$text['choose_bible'];?></button>
			</form>
				<?php

				$date = new DateTime();
				$date = set_user_timezone($date);

				echo '<br/>
				<form method="post">
					<input type="hidden" name="menu" value="timetable" />
					<input type="hidden" name="action" value="submit_own_timetable" />
					<div class="form-group">
						<label for="timetableTable">' . $text['text_title'] . '</label>
						<input type="text" name="timetable_title" class="form-control" value="' . $info_row['title'] . '" id="timetableTitle">
					</div>
					<div class="form-group">
						<label for="fromDateTimetable">' . $text['from_date'] . '</label>
						<input type="date" name="from_date" class="form-control" value="' . $date -> format('Y-m-d') . '" id="fromDateTimetable">
					</div>
					';
					foreach (['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day_of_week) 
					{
						echo '<div class="form-group">
							<label for="' . $day_of_week . '_group">' . $text['chapters_in'] . $text['text_' . $day_of_week] . '</label>
							<div class="input-group" id="' . $day_of_week . '_group">
								<span class="input-group-addon">
								<input type="checkbox" name="day_of_week[' . $day_of_week . ']" checked>
								</span>
								<input type="text" class="form-control" name="chapters_in[' . $day_of_week . ']" value="3" />
							</div>
						</div>';	
					}
					
					echo '<input type="submit" class="btn btn-default form-control" name="submit" value="' . $text['create_own_timetable'] . '" />
				</form>';
				break;
			}
			default:
			{
				break;
			}
		}
	}


?>
<br />
<form method="post">
	<div class="form-group">
		<label for="timetableDateField"><?=$text['text_date'];?></label>
		<input type="date" name="date" class="form-control" value="<?=$date->format('Y-m-d');?>" />
	</div>
	<input type="submit" class="btn btn-default form-control" name="submit" value="<?=$text['text_open'];?>">
</form>
<?php

	$timetables_statement = $pdo -> prepare('select id, title, b_code from timetables where user_id = :user_id');
	$result = $timetables_statement -> execute(['user_id' => $_SESSION['uid']]);
	$messages[] = check_result($result, $timetables_statement, $text['tt_schedules_exception'], 'Timetables select PDO query exception.');

	if ($result)
	{
		$timetables_rows = $timetables_statement -> fetchAll();
		foreach ($timetables_rows as $timetable_row) 
		{
			$today = new DateTime();//set_user_timezone(new DateTime());
			echo '<br/><h3>' . $timetable_row['title'] . '</h3><br/>';
			$select_query = 'select s.book, case when bt.title is null then s.book else bt.title end `title`, s.chapter, s.`read` from schedules s
							left join book_titles bt on s.book = bt.book
							where s.timetable_id = :timetable_id
							and s.`when` = :date';
			$select_readings_statement = $pdo -> prepare($select_query);
			$result = $select_readings_statement -> execute(['timetable_id' => $timetable_row['id'], 'date' => $today -> format('Y-m-d')]);
			$messages[] = check_result($result, $select_readings_statement, $text['tt_readings_exception'], 'Select readings for today exception.');
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

					echo '<a href="./?menu=bible&b_code=' . $timetable_row['b_code'] . '&book=' . $reading_row['book'] . '&chapter=' . $reading_row['chapter'] . '" target="_blank"><span' . $alert . '><b>' . $reading_row['title'] .'</b> ' . $reading_row['chapter'] . '</span></a> ';
				}
				echo '<br/>';
			}
		}
	}
	if($date -> format('md') != '0229')
	{
		$statement_schedules = $pdo -> prepare('select user_id, b_code, scheduled from bible_for_a_year_schedules where user_id = :user_id and scheduled is not null');
		$statement_schedules -> execute(['user_id' => $_SESSION['uid']]);
		$schedules_rows = $statement_schedules -> fetchAll();

		foreach ($schedules_rows as $row) 
		{
			$messages[] = display_bfy_schedule($row['b_code'], $date);
			echo '<br /><br /><form method="post">
					<input type="hidden" name="menu" value="timetable">
					<input type="hidden" name="action" value="unschedule">
					<input type="hidden" name="bfy_b_code" value="' . $row['b_code'] .'">
					<input type="submit" class="btn btn-warning form-control" name="submit" value="' . $text['to_unschedule'] . '"></form>';
		}

		if(stripos($b_code, 'http') === FALSE)
		{
			$messages[] = display_bfy_schedule($b_code, $date);
			echo '<br /><br /><form method="post">
					<input type="hidden" name="menu" value="timetable">
					<input type="hidden" name="action" value="schedule">
					<input type="submit" class="btn btn-default form-control" name="submit" value="' . $text['to_schedule'] . '"></form>';
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
	<input type="submit" class="btn btn-default form-control" name="submit" value="<?=$text['create_own_timetable'];?>">
</form>