<form method="post" name="countrySelectionForm" action="./">
<input type="hidden" name="menu" value="timetable" />
<input type="hidden" name="action" value="<?=$action;?>" />
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

	<form method="post" name="languageSelectionForm" action="./">
		<input type="hidden" name="menu" value="timetable" />
		<input type="hidden" name="action" value="<?=$action;?>" />
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
							$result = $statement_language -> execute(array('language' => $ttBible -> language, 'country' => $ttBible -> country));
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

	<form method="post" name="bibleSelectionForm" action="./">
		<input type="hidden" name="menu" value="timetable" />
		<input type="hidden" name="action" value="<?=$action;?>" />
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
</form><br />