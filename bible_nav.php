<script>
	<?php
		foreach(array('book_index', 'chapter_index') as $item)
		{
			if (!isset($$item))
				echo "var $item = 1;";
			else echo "var $item = " . $$item . ';';
		}

		if (!isset($verse_index))
				echo "var verse_index = 0;";
			else echo "var verse_index = " . $verse_index . ';';

		if (isset($b_code))
		{
			echo "var b_code = '$b_code';";
		}
		if (isset($book_short_title))
		{
			echo "var book_short_title = '$book_short_title';";
		}
	?>
</script>

				<form method="post" name="countrySelectionForm" action="./">
				<div class="row">
					<div class="col-md-2">
					<p>Country</p>
					</div>
					<div class="col-md-2">

						<select id="country-selection" class="bible-nav-select" style="max-width: 20em" name="country" onchange="document.countrySelectionForm.submit();" id="countryOfCountrySelectionForm">
							<?php
									
									$result_countries = mysqli_query($links['sofia']['mysql'], '
										select distinct country `country_name`, 
										case when cnt.native is null then t.country else cnt.native end `country`
										from
										(select country from b_shelf 
										union all
										select country_name from iso_ms_languages
												where language_name in (select language_name from b_shelf)
										) as t
										left join countries cnt on t.country = cnt.name
										order by country');
									if (!$result_countries)
									{
										log_msg(__FILE__ . ' ' . __LINE__ . ' ' . mysqli_error($links['sofia']['mysql']));
										//echo "Whoops. We've got issue with MySQL connection... Sorry. Please contact support. " . mysqli_error($links['sofia']['mysql']);;
										//print_r($links['sofia']['mysql']);

									}
									else
									{
										$statement_country = $links['sofia']['pdo'] -> prepare('select country_name `country` from iso_ms_languages where country_code = :country or country_name = :country');
										$result_country = $statement_country -> execute(array('country' => $country));
										//print_r($statement_country);
										if (!$result_country)
										{	
											log_msg(__FILE__ . ' ' . __LINE__ . ' ' . $statement_country -> errorInfo());
											//echo "Whoops. We've got issue with PDO connection... Sorry. Please contact support.";
											//print_r($statement_country -> errorInfo());
										}
										$country_row = $statement_country -> fetch();
										$country_name = strtolower($country_row['country']);
										while ($row = mysqli_fetch_assoc($result_countries)) 
										{
											$selected = '';
											//echo "<!-- `$country_name` ?= `" . strtolower($row['country']) . "` for `$country` -->";
											if ($country_name == strtolower($row['country_name']))
											{
												$selected = ' selected'; 
											}
											echo '<option value="' . $row['country_name'] . '"' . $selected . '>' . $row['country'] . '</option>';
										}
									}
							?>
						</select>
					</div>
				</div>
				</form>

				<form method="post" name="languageSelectionForm" action="./">
					<input type="hidden" name="country" value="<?=$country;?>" id="countryOfLanguageSelectionForm" />
				<div class="row">
					<div class="col-md-2">
						<p>Language</p>
					</div>
					<div class="col-md-2">
						<select id="languageOfLanguageSelectionForm" style="max-width: 20em" name="language" onchange="document.languageSelectionForm.country.value = document.countrySelectionForm.country.value; document.languageSelectionForm.submit();">
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
										log_msg(__FILE__ . ' ' . __LINE__ . ' ' . $statement_languages -> errorInfo());
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
										$result = $statement_language -> execute(array('language' => $language, 'country' => $country_name));
										if (!$result)
										{
											log_msg(__FILE__ . ' ' . __LINE__ . ' ' . $statement_language -> errorInfo());
											//echo "Whoops. We've got issue with PDO connection... Sorry. Please contact support.";
											//print_r($links['sofia']['pdo'] -> errorInfo());
										}
										$language_rows = $statement_language -> fetchAll();
										$language_name = '';
										foreach ($language_rows as $item) 
										{
											if ($item['language_name'] == $language)
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
											if ($language_name == strtolower($row['language_name']))
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
					</div>
				</div> <!-- row -->
				</form>

				<form method="post" name="bibleSelectionForm" action="./">
				<div class="row">
					<div class="col-md-2">
						<p>Bible</p>
					</div>
					<div class="col-md-2">
						<input type="hidden" name="country" value="<?=$country;?>" />
						<input type="hidden" name="language" value="<?=$language;?>" />
						<select id="bible-selection" style="max-width: 20em" name="b_code" onchange="document.bibleSelectionForm.country.value = document.countrySelectionForm.country.value; bibleSelectionForm.language.value = document.languageSelectionForm.language.value; document.bibleSelectionForm.submit();">
							<?php
									$statement_bibles = $links['sofia']['pdo'] -> prepare(
											'select b_code, title from b_shelf where language = :language_name order by title');
										$result_bibles = $statement_bibles -> execute(array('language_name' => $language_name));

									if (!$result_bibles)
									{
										log_msg(__FILE__ . ' ' . __LINE__ . ' ' . $statement_bibles -> errorInfo());
										//echo "Whoops. We've got issue with PDO connection... Sorry. Please contact support. ";
										//print_r($links['sofia']['pdo']);

									}
									else
									{
										while ($row = $statement_bibles -> fetch()) 
										{
											$selected = '';
											if (isset($b_code) and $b_code == strtolower($row['b_code']))
											{
												$selected = ' selected="selected"'; 
											}
											echo '<option value="' . $row['b_code'] . '"' . $selected . '>' . $row['title'] . '</option>';
										}
									}			
							?>
						</select>
					</div>
				</div> <!-- row -->

				<div class="row">
					<div class="col-md-12">
						<p align="right"><button class="btn btn-primary" onclick="">Open Bible</button></p>
					</div>
				</div>
				</form>









