<?php

	class UserBible
	{
		public $country;
		public $language;
		public $language_code;
		public $b_code;
		public $table_name;
		public $title;
		public $pdo;
		public $mysql;

		function __construct($pdo, $mysql)
		{
			$this -> pdo = $pdo;
			$this -> mysql = $mysql;
		}

		function setCountry($country)
		{
			$country = strtolower($country);
			$query = 'select distinct country `country_name`, 
												case when cnt.native is null then t.country else cnt.native end `country`
										from
										(select country from b_shelf 
										union all
										select country_name from iso_ms_languages
												where language_name in (select language_name from b_shelf)
										) as t
										left join countries cnt on t.country = cnt.name';
			$result_countries = mysqli_query($this -> mysql, $query);

			if (!$result_countries)
			{
				log_msg(__FILE__ . ':' . __CLASS__ . ':' . __LINE__ . ' 
					Countries SQL exception. Info = {' . mysqli_error($this -> mysql) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
			}
			else
			{
				$statement_country = $this -> pdo -> prepare('select country_name `country` from iso_ms_languages where country_code = :country or country_name = :country');
				$result_country = $statement_country -> execute(array('country' => $country));

				if (!$result_country)
				{	
					log_msg(__FILE__ . ':' . __LINE__ . ' Countries PDO query exception. Info = {' . json_encode($statement_country -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
				}
				$country_row = $statement_country -> fetch();
				$country_name = strtolower($country_row['country']);

				$country_found = FALSE;
				while ($row = mysqli_fetch_assoc($result_countries)) 
				{
					if (strcasecmp($country_name, $row['country_name']) === 0)
					{
						$this -> country = $country_name;
						$country_found = true;
					}
				}
				if(!$country_found)
					$this -> country = 'United States';
			}

		} // function setCountry

		function setLanguage($language)
		{
			$language = strtolower($language);
			$query = 'select distinct t.language_name, case when iso_ms.native_language_name is null then 				t.language_name else iso_ms.native_language_name end `native_language_name`, iso_ms.iso_language_code from
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
										';
			$statement_languages = $this -> pdo -> prepare($query);

			$result_languages = $statement_languages -> execute(array('country_name' => $this -> country));

			if (!$result_languages)
			{
				log_msg(__FILE__ . ':' . __LINE__ . ' Languages PDO query exception. Info = {' . json_encode($statement_languages -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
			}
			else
			{
				$statement_language = $this -> pdo -> prepare('
					select language_name, iso_language_code from iso_ms_languages where lower(language_code) = :language and language_name in (select distinct language from b_shelf)
					union
					select language_name, iso_language_code from iso_ms_languages where language_name = :language and language_name in (select distinct language from b_shelf)
					union
					select language_name, iso_language_code from iso_ms_languages where country_name = :country  and language_name in (select distinct language from b_shelf)
					union
					select language_name, iso_language_code from iso_ms_languages where country_code = :country and language_name in (select distinct language from b_shelf)
					union
					select b_shelf.language `language_name`, iml.iso_language_code  from b_shelf
						join iso_ms_languages iml on b_shelf.language = iml.language_name
						where language = :language
					');
				/*

					union
					select b_shelf.language `language_name` from b_shelf where country = :country
				*/
				$result = $statement_language -> execute(array('language' => $language, 'country' => $this -> country));

				if (!$result)
				{
					log_msg(__FILE__ . ':' . __LINE__ . ' Languages PDO query exception. Info = {' . json_encode($statement_language -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');
				}

				$language_rows = $statement_language -> fetchAll();
				
				$language_name = '';
				foreach ($language_rows as $item) 
				{
					if ( strcasecmp($item['language_name'], $language) === 0)
						$language_name = strtolower($item['language_name']);
				}

				if (strcasecmp($language_name, $language) !== 0)
					$language_name = $language_rows[0]['language_name'];
				else
					$language_name = $language;

				//log_msg(__FILE__ . ':' . __LINE__ . " \$language_name = `$language_name`");

				$found_language = FALSE;
				$languages_rows = $statement_languages -> fetchAll();
				foreach ($languages_rows as $row) 
				{
					if (strcasecmp($language_name, $row['language_name']) === 0 )
					{
						$found_language = true;
						$this -> language = $language_name;
						$this -> language_code = strtolower($row['iso_language_code']);
						break;
					}
				}
				if(!$found_language)
				{
					$this -> language = strtolower($languages_rows[0]['language_name']);
					$this -> language_code = strtolower($languages_rows[0]['iso_language_code']);
				}
			}
		}// function setLanguage

		function setBCode($b_code)
		{
			$statement_bibles = $this -> pdo -> prepare(
					'select b_code, table_name, title from b_shelf where language = :language_name');
				$result_bibles = $statement_bibles -> execute(array('language_name' => $this -> language));

			if (!$result_bibles)
			{
				log_msg(__FILE__ . ':' . __LINE__ . ' Bibles PDO query exception. Info = {' . json_encode($statement_bibles -> errorInfo()) . '}, $_REQUEST = {' . json_encode($_REQUEST) . '}');

			}
			else
			{
				$bibles_rows = $statement_bibles -> fetchAll();
				$bible_found = FALSE;
				foreach ($bibles_rows as $row) 
				{
					if (strcasecmp($b_code, $row['b_code']) === 0)
					{
						$this -> b_code = $row['b_code'];
						$this -> table_name = $row['table_name'];
						$this -> title = $row['title'];
						$bible_found = true;
						break;
					}
				}
				if(!$bible_found)
				{
					$this -> b_code = $bibles_rows[0]['b_code'];
					$this -> table_name = $bibles_rows[0]['table_name'];
					$this -> title = $bibles_rows[0]['title'];
				}
			}		
		} // function setBCode
	}

	class Bible
	{
		public $table_name;
		public $b_code;
	}

?>