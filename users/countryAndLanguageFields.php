	<br />
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['text_country'];?></p>
		</div>
		<div class="col-md-2">
			<select name="user_country">
				<?php
					$result_countries = mysqli_query( $links['sofia']['mysql'],
								'select code, name, native from countries order by native');

							//log_msg(__FILE__ .':' . __LINE__ . ' cmp country = ' . $cmp_country);
					if (!$result_countries)
						log_msg(__FILE__ . ':' . __LINE__ .' ' . mysqli_error($result_countries));
					else
					while ($country_row = mysqli_fetch_assoc($result_countries))
					{
						$selected = '';
						if ( (strcasecmp($cmp_country, $country_row['code']) === 0) 
							or(strcasecmp($cmp_country, $country_row['name']) === 0) )
						{
							//log_msg(__FILE__ .':' . __LINE__ . ' cmp country = ' . $cmp_country . ', $country_code = ' . $country_row['code']);
							$selected = ' selected="selected"';
						}
						echo '<option value="' . $country_row['name'] . '"' . $selected . '>' . $country_row['native'] . '</option>';
					}
				?>
			</select>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="col-md-2">
			<p><?=$text['text_language'];?></p>
		</div>
		<div class="col-md-2">
			<select name="user_language">
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
						echo '<option value="' . $language_row['language_name'] . '"' . $selected . '>' . $language_row['native_name'] . '</option>';
					}
				?>
			</select>
		</div>
	</div>