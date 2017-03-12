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

<p align="right">
	<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#bibleSelectionDialog">Open Bible</button>
</p>

<div class="modal fade" tabindex="-1" role="dialog" id="bibleSelectionDialog" aria-labelledby="bibleSelectionDialogLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 id="bibleSelectionDialogLabel" class="modal-title">Open Bible</h3>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-2">
					<p>Country</p>
					</div>
					<div class="col-md-2">
						<script src="./js/get_bible_languages.js"></script>

						<select id="country-selection" class="bible-nav-select" onchange="get_bible_languages();" style="max-width: 20em">
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
										log_msg(__FILE__ . ':' . __LINE__ . ' Countries SQL query exception. Info = {' . mysqli_error($links['sofia']['mysql']) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
										//echo "Whoops. We've got issue with MySQL connection... Sorry. Please contact support. " . mysqli_error($links['sofia']['mysql']);;
										//print_r($links['sofia']['mysql']);

									}
									else
									{
										$statement_country = $links['sofia']['pdo'] -> prepare('select country_name `country` from iso_ms_languages where country_code = :country_code');
										$result_country = $statement_country -> execute(array('country_code' => $country));
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
				<div class="row">
					<div class="col-md-2">
						<p>Language</p>
					</div>
					<div class="col-md-2">
						<script src="./js/get_bible_titles.js"></script>
						<select id="language-selection" onchange="get_bible_titles()" style="max-width: 20em">
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

										$statement_language = $links['sofia']['pdo'] -> prepare('select language_name from iso_639_languages where lower(code) = :language_code');
										$result = $statement_language -> execute(array('language_code' => $language));
										if (!$result)
										{
											log_msg(__FILE__ . ':' . __LINE__ . ' Languages PDO query exception. Info = {' . json_encode($statement_language -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
											//echo "Whoops. We've got issue with PDO connection... Sorry. Please contact support.";
											//print_r($links['sofia']['pdo'] -> errorInfo());
										}
										$language_row = $statement_language -> fetch();
										$language_name = strtolower($language_row['language_name']);
										echo "<script>var language_name ='$language_name';</script>";
										while ($row = $statement_languages -> fetch()) 
										{
											$selected = '';
											if ($language_name == strtolower($row['language_name']))
											{
												$selected = ' selected="selected"'; 
											}
											echo '<option value="' . $row['language_name'] . '"' . $selected . '>' . $row['native_language_name'] . '</option>';
										}
									}			
							?>			
						</select>
					</div>
				</div> <!-- row -->

				<div class="row">
					<div class="col-md-2">
						<p>Bible</p>
					</div>
					<div class="col-md-2">
						<select id="bible-selection" style="max-width: 20em">
							<?php
									$statement_bibles = $links['sofia']['pdo'] -> prepare(
											'select b_code, title from b_shelf where language = :language_name order by title');
										$result_bibles = $statement_bibles -> execute(array('language_name' => $language_name));

									if (!$result_bibles)
									{
										log_msg(__FILE__ . ':' . __LINE__ . ' Bibles PDO query exception. Info = {' . $statement_bibles -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
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
			</div> <!-- div class modal body -->
			<div class="modal-footer">
				<script src="./js/get_bible_verses.js"></script>
				<script src="./js/get_bible_info.js"></script>
				<script src="./js/get_books.js"></script>
				<script src="./js/get_book_info.js"></script>
				<script src="./js/get_chapters.js"></script>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="book_index=1; chapter_index=1;get_bible_info(); get_bible_verses();">Open Bible</button>
			</div>
		</div> <!-- div class modal-content -->
	</div> <!-- div class modal-dialog -->
</div>