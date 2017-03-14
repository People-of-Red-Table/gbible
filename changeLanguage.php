<h1><?=$text['text_int_languages'];?></h1>
<br />
<form method="post">
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['text_language'];?></p>
		</div>
		<div class="col-md-2">
			<select name="interface_language">
				<?php
					$result_languages = mysqli_query( $links['sofia']['mysql'],
								'select language_name, native_name, language_code from iso_639_1_languages
								where language_code in ("' . implode('", "', $languages) . '")
								 order by native_name');

						//	log_msg(__FILE__ .':' . __LINE__ . ' cmp language = ' . $cmp_language);
					if (!$result_languages)
						log_msg(__FILE__ . ':' . __LINE__ .' ' . mysqli_error($result_languages));
					else
					while ($language_row = mysqli_fetch_assoc($result_languages))
					{
						$selected = '';
						if ( (strcasecmp($cmp_language, $language_row['language_code']) === 0)
							 or (strcasecmp($cmp_language, $language_row['language_name']) === 0))
						{
						//	log_msg(__FILE__ .':' . __LINE__ . ' cmp language = ' . $cmp_language . ', language_code = ' . $language_row['language_code']);
							$selected = ' selected="selected"';
						}
						echo '<option value="' . $language_row['language_code'] . '"' . $selected . '>' . $language_row['native_name'] . '</option>';
					}
				?>
			</select>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-2">
			<input type="hidden" name="menu" value="bible" />
			<input type="submit" name="submit" value="<?=$text['choose_language']?>" />
		</div>
	</div>
</form>