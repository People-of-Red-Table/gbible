<?php
	$tt_b_code1 = $tt_userBibleA -> b_code;
	$tt_b_code2 = $tt_userBibleB -> b_code;
?>
<div class="row">
	<div class="col-md-6 hidden-print">
		<!-- Bible 1 -->
			<br/><h5><b><?=$text['bible_a'];?></b></h5>
			<form method="post" name="countrySelectionForm1">
				<input type="hidden" name="menu" value="parallelBibles" />
				<div class="form-group">
					<label for="countryOfCountrySelectionForm1"><?=$text['text_country'];?></label>
					<select class="bible-nav-select form-control" name="tt_country1" onchange="document.countrySelectionForm1.submit();" id="countryOfCountrySelectionForm1">
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
										$result_country = $statement_country -> execute(array('country' => $tt_userBibleA -> country));
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

				<form method="post" name="languageSelectionForm1">
					<input type="hidden" name="menu" value="parallelBibles" />
					<input type="hidden" name="tt_country1" value="<?=$tt_country1;?>" id="countryOfLanguageSelectionForm1" />
				<div class="form-group">
						<label for="languageOfLanguageSelectionForm1"><?=$text['text_language'];?></label>
						<select id="languageOfLanguageSelectionForm1" class="form-control" name="tt_language1" onchange="document.languageSelectionForm1.tt_country1.value = document.countrySelectionForm1.tt_country1.value; document.languageSelectionForm1.submit();">
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
										$result_languages = $statement_languages -> execute(array('country_name' => $country_name));

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
										$result = $statement_language -> execute(array('language' => $tt_userBibleA -> language, 'country' => $country_name));
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
											if (strcasecmp($item['language_name'], $language) === 0)
												$language_name = $item['language_name'];
										}
										if (empty($language_name))
											$language_name = $language_rows[0]['language_name'];

										//log_msg(__FILE__ . ':' . __LINE__ . " \$language_name = `$language_name`");
										echo "<script>var language_name ='$language_name';</script>";
										$found_language = FALSE;
										while ($row = $statement_languages -> fetch()) 
										{
											$selected = '';
											if (strcasecmp($language_name, $row['language_name']) === 0 )
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

				<form method="post" name="bibleSelectionForm1">
					<input type="hidden" name="menu" value="parallelBibles" />
				<div class="form-group">
						<label for="bible-selection-1"><?=$text['text_bible'];?></label>
						<input type="hidden" name="tt_country1" value="<?=$tt_country1;?>" />
						<input type="hidden" name="tt_language1" value="<?=$tt_language1;?>" />
						<select id="bible-selection-1" class="form-control" name="tt_b_code1" onchange="document.bibleSelectionForm1.tt_country1.value = document.countrySelectionForm1.tt_country1.value; bibleSelectionForm1.tt_language1.value = document.languageSelectionForm1.tt_language1.value; document.bibleSelectionForm1.submit();">
							<?php
									$statement_bibles = $links['sofia']['pdo'] -> prepare(
											'select b_code, title from b_shelf where language = :language_name order by title');
										$result_bibles = $statement_bibles -> execute(array('language_name' => $language_name));

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
											if (isset($tt_b_code1) and (strcasecmp($tt_b_code1, $row['b_code']) ===0))
											{
												$selected = ' selected="selected"'; 
											}
											echo '<option value="' . $row['b_code'] . '"' . $selected . '>' . $row['title'] . '</option>';
										}
									}			
							?>
						</select>
				</div> <!-- row -->

				<button class="form-control btn btn-primary" onclick=""><?=$text['open_bible'];?></button>
			</form>

	</div>

	<div class="col-md-6">
		<!-- Bible 2 -->
		<br/><h5><b><?=$text['bible_b'];?></b></h5>
		<form method="post" name="countrySelectionForm2">
				<input type="hidden" name="menu" value="parallelBibles" />
				<div class="form-group">
					<label for="countryOfCountrySelectionForm2"><?=$text['text_country'];?></label>
					<select class="bible-nav-select form-control" name="tt_country2" onchange="document.countrySelectionForm2.submit();" id="countryOfCountrySelectionForm2">
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
										$result_country = $statement_country -> execute(array('country' => $tt_userBibleB -> country));
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

				<form method="post" name="languageSelectionForm2">
					<input type="hidden" name="menu" value="parallelBibles" />
					<input type="hidden" name="tt_country2" value="<?=$tt_country2;?>" id="countryOfLanguageSelectionForm2" />
				<div class="form-group">
						<label for="languageOfLanguageSelectionForm2"><?=$text['text_language'];?></label>
						<select id="languageOfLanguageSelectionForm2" class="form-control" name="tt_language2" onchange="document.languageSelectionForm2.tt_country2.value = document.countrySelectionForm2.tt_country2.value; document.languageSelectionForm2.submit();">
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
										$result_languages = $statement_languages -> execute(array('country_name' => $country_name));

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
										$result = $statement_language -> execute(array('language' => $tt_userBibleB -> language, 'country' => $country_name));
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
											if (strcasecmp($item['language_name'], $tt_userBibleB -> language) === 0)
												$language_name = strtolower($item['language_name']);
										}
										if (empty($language_name))
											$language_name = strtolower($language_rows[0]['language_name']);

										//log_msg(__FILE__ . ':' . __LINE__ . " \$language_name = `$language_name`");
										echo "<script>var language_name ='$language_name';</script>";
										$found_language = FALSE;
										while ($row = $statement_languages -> fetch()) 
										{
											$selected = '';
											if (strcasecmp($language_name, $row['language_name']) === 0)
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

				<form method="post" name="bibleSelectionForm2">
					<input type="hidden" name="menu" value="parallelBibles" />
				<div class="form-group">
						<label for="bible-selection-2"><?=$text['text_bible'];?></label>
						<input type="hidden" name="tt_country2" value="<?=$tt_country2;?>" />
						<input type="hidden" name="tt_language2" value="<?=$tt_language2;?>" />
						<select id="bible-selection-2" class="form-control" name="tt_b_code2" onchange="document.bibleSelectionForm2.tt_country2.value = document.countrySelectionForm2.tt_country2.value; bibleSelectionForm2.tt_language2.value = document.languageSelectionForm2.tt_language2.value; document.bibleSelectionForm2.submit();">
							<?php
									$statement_bibles = $links['sofia']['pdo'] -> prepare(
											'select b_code, title from b_shelf where language = :language_name order by title');
										$result_bibles = $statement_bibles -> execute(array('language_name' => $language_name));

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
											if (isset($tt_b_code2) and (strcasecmp($tt_b_code2, $row['b_code']) === 0) )
											{
												$selected = ' selected="selected"'; 
											}
											echo '<option value="' . $row['b_code'] . '"' . $selected . '>' . $row['title'] . '</option>';
										}
									}			
							?>
						</select>
				</div> <!-- row -->

				<button class="form-control btn btn-primary" onclick=""><?=$text['open_bible'];?></button>
			</form>

	</div>
</div><br />